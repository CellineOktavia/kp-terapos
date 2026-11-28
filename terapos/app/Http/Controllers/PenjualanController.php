<?php

namespace App\Http\Controllers;

use App\Models\DetailPenjualan;
use App\Models\DiscountSetting;
use App\Models\Penjualan;
use App\Models\Product;
use App\Models\StockMovement;
use App\Models\User;
use App\Services\DiscountCalculator;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

class PenjualanController extends Controller
{
    public function index(Request $request)
    {
        $endDateRules = ['nullable', 'date'];
        if ($request->filled('start_date')) {
            $endDateRules[] = 'after_or_equal:start_date';
        }

        $filters = $request->validate([
            'search' => ['nullable', 'string', 'max:100'],
            'start_date' => ['nullable', 'date'],
            'end_date' => $endDateRules,
            'user_id' => [
                'nullable',
                'integer',
                Rule::exists('users', 'id'),
            ],
        ]);

        $penjualans = Penjualan::query()
            ->with(['user'])
            ->withCount('detailPenjualans')
            ->withSum('detailPenjualans', 'qty')
            ->withSum('returnedDetails as returned_qty', 'qty')
            ->withSum('returns as returned_amount', 'refund_amount')
            ->when($filters['search'] ?? null, function ($query, $search) {
                $query->where(function ($saleQuery) use ($search) {
                    $saleQuery->where('nomor_penjualan', 'like', "%{$search}%")
                        ->orWhereHas('user', function ($userQuery) use ($search) {
                            $userQuery->where('name', 'like', "%{$search}%");
                        })
                        ->orWhereHas('detailPenjualans.product', function ($productQuery) use ($search) {
                            $productQuery->where('nama_produk', 'like', "%{$search}%")
                                ->orWhere('kode_produk', 'like', "%{$search}%");
                        });
                });
            })
            ->when($filters['start_date'] ?? null, fn($query, $date) => $query->whereDate('tanggal', '>=', $date))
            ->when($filters['end_date'] ?? null, fn($query, $date) => $query->whereDate('tanggal', '<=', $date))
            ->when($filters['user_id'] ?? null, fn($query, $userId) => $query->where('user_id', $userId))
            ->latest()
            ->paginate(25)
            ->withQueryString();

        $users = User::query()
            ->whereIn('role', ['owner', 'co_owner'])
            ->orderBy('name')
            ->get();

        return view('penjualan.index', compact('penjualans', 'users', 'filters'));
    }

    public function create()
    {
        $products = Product::query()
            ->with('category:id,nama_kategori')
            ->where('aktif', true)
            ->orderBy('nama_produk')
            ->get(['id', 'category_id', 'kode_produk', 'nama_produk', 'merk', 'satuan', 'harga_beli', 'harga_jual', 'stok']);
        $generatedNumber = $this->generateNextSaleNumber();
        $discountSetting = DiscountSetting::current();
        $requestToken = (string) Str::uuid();

        return view('penjualan.create', compact(
            'products',
            'generatedNumber',
            'discountSetting',
            'requestToken'
        ));
    }

    public function store(Request $request)
    {
        $request->merge([
            'request_token' => $request->input('request_token') ?: (string) Str::uuid(),
        ]);
        $request->merge([
            'bayar' => is_string($request->input('bayar'))
                ? preg_replace('/[^\d]/', '', $request->input('bayar'))
                : $request->input('bayar'),
        ]);
        $validated = $request->validate([
            'tanggal' => ['required', 'date'],
            'request_token' => ['required', 'uuid'],
            'items' => ['required', 'array', 'min:1'],
            'items.*.product_id' => [
                'required',
                'distinct',
                'exists:products,id',
            ],
            'items.*.qty' => [
                'required',
                'integer',
                'min:1',
            ],
            'bayar' => ['required', 'numeric', 'min:0'],
        ]);

        for ($attempt = 1;; $attempt++) {
            try {
                [$saleId, $saleNumber] = DB::transaction(function () use ($validated) {
                    $existingSale = Penjualan::query()
                        ->where('request_token', $validated['request_token'])
                        ->first();

                    if ($existingSale) {
                        return [$existingSale->id, $existingSale->nomor_penjualan];
                    }

                    $saleNumber = $this->generateNextSaleNumber();
                    $items = collect($validated['items']);
                    $productIds = $items->pluck('product_id')
                        ->map(fn($id) => (int) $id)
                        ->unique()
                        ->sort()
                        ->values();

                    $products = Product::query()
                        ->whereIn('id', $productIds)
                        ->orderBy('id')
                        ->lockForUpdate()
                        ->get()
                        ->keyBy('id');

                    if ($products->count() !== $productIds->count()
                        || $products->contains(fn (Product $product) => !$product->aktif)) {
                        throw ValidationException::withMessages([
                            'items' => 'Salah satu barang tidak lagi aktif atau tersedia.',
                        ]);
                    }

                    foreach ($items as $item) {
                        $product = $products->get((int) $item['product_id']);
                        $qty = (int) $item['qty'];

                        if ($product->stok < $qty) {
                            throw ValidationException::withMessages([
                                'items' => "Stok {$product->nama_produk} tidak mencukupi. Stok tersedia: {$product->stok}.",
                            ]);
                        }
                    }

                    $discountSetting = DiscountSetting::query()->whereKey(1)->lockForUpdate()->firstOrFail();
                    $lines = [];
                    $subtotalPenjualan = 0.0;
                    foreach ($items as $item) {
                        $product = $products->get((int) $item['product_id']);
                        $qty = (int) $item['qty'];
                        $lineSubtotal = round($qty * (float) $product->harga_jual, 2);
                        $lines[$product->id] = [
                            'product' => $product,
                            'qty' => $qty,
                            'subtotal' => $lineSubtotal,
                        ];
                        $subtotalPenjualan += $lineSubtotal;
                    }

                    $subtotalPenjualan = round($subtotalPenjualan, 2);
                    $discount = app(DiscountCalculator::class)->calculate($subtotalPenjualan, $discountSetting);
                    $discountShares = [];
                    $totalWeight = (int) round($subtotalPenjualan * 100);
                    $allocated = 0;
                    $totalCents = (int) round((float) $discount['discount'] * 100);
                    foreach ($lines as $productId => $line) {
                        $weight = (int) round((float) $line['subtotal'] * 100);
                        $share = $totalWeight > 0 ? (int) floor($totalCents * $weight / $totalWeight) : 0;
                        $discountShares[$productId] = $share / 100;
                        $allocated += $share;
                    }
                    if ($lines && $totalCents > $allocated) {
                        $firstId = array_key_first($lines);
                        $discountShares[$firstId] += ($totalCents - $allocated) / 100;
                    }
                    $diskonPersen = $discount['percentage'];
                    $diskonNominal = $discount['discount'];
                    $total = $discount['total'];
                    $bayar = round((float) $validated['bayar'], 2);

                    if ($bayar < $total) {
                        throw ValidationException::withMessages([
                            'bayar' => 'Jumlah pembayaran harus sama dengan atau lebih besar dari total penjualan.',
                        ]);
                    }

                    $penjualan = Penjualan::create([
                        'nomor_penjualan' => $saleNumber,
                        'request_token' => $validated['request_token'],
                        'user_id' => Auth::id(),
                        'tanggal' => $validated['tanggal'],
                        'diskon_persen' => $diskonPersen,
                        'diskon_nominal' => $diskonNominal,
                        'diskon_aktif' => $discountSetting->is_active,
                        'diskon_minimum_pembelian' => $discountSetting->minimum_purchase,
                        'diskon_operator' => $discountSetting->condition_operator,
                        'cross_subsidy_amount' => 0,
                        'status' => 'completed',
                        'total' => $total,
                        'bayar' => $bayar,
                        'kembalian' => round($bayar - $total, 2),
                    ]);

                    $details = [];
                    foreach ($lines as $productId => $line) {
                        /** @var Product $product */
                        $product = $line['product'];
                        $qty = $line['qty'];
                        $detail = DetailPenjualan::create([
                            'penjualan_id' => $penjualan->id,
                            'product_id' => $product->id,
                            'qty' => $qty,
                            'harga' => $product->harga_jual,
                            'subtotal' => $line['subtotal'],
                            'discount_share' => $discountShares[$productId] ?? 0,
                            'cross_subsidy_per_unit' => 0,
                        ]);
                        $details[$productId] = $detail;

                        $product->stok -= $qty;
                        $product->save();

                        StockMovement::create([
                            'product_id' => $product->id,
                            'jenis' => 'keluar',
                            'qty' => $qty,
                            'keterangan' => 'Penjualan ' . $saleNumber,
                        ]);
                    }

                    return [$penjualan->id, $saleNumber];
                });

                break;
            } catch (QueryException $exception) {
                if ($this->isRequestTokenCollision($exception)) {
                    $existingSale = Penjualan::query()
                        ->where('request_token', $validated['request_token'])
                        ->first();

                    if ($existingSale) {
                        $saleId = $existingSale->id;
                        $saleNumber = $existingSale->nomor_penjualan;
                        break;
                    }
                }

                if ($attempt >= 5 || !$this->isSaleNumberCollision($exception)) {
                    throw $exception;
                }
            }
        }

        return redirect()
            ->route('penjualan.show', $saleId)
            ->with('success', "Penjualan {$saleNumber} berhasil disimpan.");
    }

    public function show(Penjualan $penjualan)
    {
        $penjualan->load([
            'user',
            'detailPenjualans.product',
            'detailPenjualans.returns',
            'returns.user',
            'returns.details.detailPenjualan.product',
        ]);
        $returnToken = (string) Str::uuid();

        return view('penjualan.show', compact('penjualan', 'returnToken'));
    }

    public function receiptPdf(Penjualan $penjualan)
    {
        $penjualan->load(['user', 'detailPenjualans.product']);

        return Pdf::loadView('penjualan.receipt-pdf', compact('penjualan'))
            ->setPaper([0, 0, 226.77, 600], 'portrait')
            ->download('nota-' . $penjualan->nomor_penjualan . '.pdf');
    }

    private function generateNextSaleNumber(): string
    {
        $maxSequence = Penjualan::query()
            ->pluck('nomor_penjualan')
            ->reduce(function (int $largest, string $number): int {
                if (preg_match('/^TRX(\d{6})$/', $number, $matches)) {
                    return max($largest, (int) $matches[1]);
                }

                return $largest;
            }, 0);

        $sequence = $maxSequence + 1;
        if ($sequence > 999999) {
            throw new \RuntimeException('Nomor penjualan telah mencapai batas sequence enam digit.');
        }

        do {
            $number = 'TRX' . str_pad((string) $sequence, 6, '0', STR_PAD_LEFT);
            $sequence++;
        } while (Penjualan::where('nomor_penjualan', $number)->exists());

        return $number;
    }

    private function isSaleNumberCollision(QueryException $exception): bool
    {
        $sqlState = $exception->errorInfo[0] ?? null;
        $message = strtolower(
            $exception->getPrevious()?->getMessage() ?? $exception->getMessage()
        );

        return in_array($sqlState, ['23000', '23505'], true)
            && str_contains($message, 'nomor_penjualan');
    }

    private function isRequestTokenCollision(QueryException $exception): bool
    {
        $sqlState = $exception->errorInfo[0] ?? null;
        $message = strtolower(
            $exception->getPrevious()?->getMessage() ?? $exception->getMessage()
        );

        return in_array($sqlState, ['23000', '23505'], true)
            && str_contains($message, 'request_token');
    }
}
