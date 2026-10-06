<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\DetailPenjualan;
use App\Models\Penjualan;
use App\Models\Product;
use App\Models\StockMovement;
use App\Models\User;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
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
            'user_id' => ['nullable', 'integer', 'exists:users,id'],
        ]);

        $penjualans = Penjualan::query()
            ->with(['customer', 'user'])
            ->withCount('detailPenjualans')
            ->withSum('detailPenjualans', 'qty')
            ->when($filters['search'] ?? null, function ($query, $search) {
                $query->where(function ($saleQuery) use ($search) {
                    $saleQuery->where('nomor_penjualan', 'like', "%{$search}%")
                        ->orWhereHas('customer', function ($customerQuery) use ($search) {
                            $customerQuery->where('nama_customer', 'like', "%{$search}%");
                        })
                        ->orWhereHas('user', function ($userQuery) use ($search) {
                            $userQuery->where('name', 'like', "%{$search}%");
                        })
                        ->orWhereHas('detailPenjualans.product', function ($productQuery) use ($search) {
                            $productQuery->where('nama_produk', 'like', "%{$search}%")
                                ->orWhere('kode_produk', 'like', "%{$search}%");
                        });
                });
            })
            ->when($filters['start_date'] ?? null, fn ($query, $date) => $query->whereDate('tanggal', '>=', $date))
            ->when($filters['end_date'] ?? null, fn ($query, $date) => $query->whereDate('tanggal', '<=', $date))
            ->when($filters['user_id'] ?? null, fn ($query, $userId) => $query->where('user_id', $userId))
            ->latest()
            ->paginate(25)
            ->withQueryString();

        $users = User::query()->orderBy('name')->get(['id', 'name']);

        return view('penjualan.index', compact('penjualans', 'users', 'filters'));
    }

    public function create()
    {
        $customers = Customer::where('aktif', true)->orderBy('nama_customer')->get();
        $products = Product::orderBy('nama_produk')->get();
        $generatedNumber = $this->generateNextSaleNumber();

        return view('penjualan.create', compact('customers', 'products', 'generatedNumber'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'tanggal' => ['required', 'date'],
            'customer_id' => ['nullable', 'exists:customers,id'],
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

        for ($attempt = 1; ; $attempt++) {
            try {
                DB::transaction(function () use ($validated) {
                    $saleNumber = $this->generateNextSaleNumber();
                    $items = collect($validated['items']);
                    $productIds = $items->pluck('product_id')
                        ->map(fn ($id) => (int) $id)
                        ->unique()
                        ->sort()
                        ->values();

                    $products = Product::query()
                        ->whereIn('id', $productIds)
                        ->orderBy('id')
                        ->lockForUpdate()
                        ->get()
                        ->keyBy('id');

                    if ($products->count() !== $productIds->count()) {
                        throw ValidationException::withMessages([
                            'items' => 'Salah satu produk tidak lagi tersedia.',
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

                    $penjualan = Penjualan::create([
                        'nomor_penjualan' => $saleNumber,
                        'customer_id' => $validated['customer_id'] ?? null,
                        'user_id' => Auth::id(),
                        'tanggal' => $validated['tanggal'],
                        'total' => 0,
                        'bayar' => 0,
                        'kembalian' => 0,
                    ]);

                    $total = 0;
                    foreach ($items as $item) {
                        $product = $products->get((int) $item['product_id']);
                        $qty = (int) $item['qty'];
                        $harga = (float) $product->harga_jual;
                        $subtotal = round($qty * $harga, 2);
                        $total += $subtotal;

                        DetailPenjualan::create([
                            'penjualan_id' => $penjualan->id,
                            'product_id' => $product->id,
                            'qty' => $qty,
                            'harga' => $harga,
                            'subtotal' => $subtotal,
                        ]);

                        $product->stok -= $qty;
                        $product->save();

                        StockMovement::create([
                            'product_id' => $product->id,
                            'jenis' => 'keluar',
                            'qty' => $qty,
                            'keterangan' => 'Penjualan ' . $saleNumber,
                        ]);
                    }

                    $total = round($total, 2);
                    $bayar = round((float) $validated['bayar'], 2);

                    if ($bayar < $total) {
                        throw ValidationException::withMessages([
                            'bayar' => 'Jumlah pembayaran harus sama dengan atau lebih besar dari total penjualan.',
                        ]);
                    }

                    $penjualan->update([
                        'total' => $total,
                        'bayar' => $bayar,
                        'kembalian' => round($bayar - $total, 2),
                    ]);
                });

                break;
            } catch (QueryException $exception) {
                if ($attempt >= 5 || !$this->isSaleNumberCollision($exception)) {
                    throw $exception;
                }
            }
        }

        return redirect()
            ->route('penjualan.index')
            ->with('success', 'Penjualan berhasil disimpan.');
    }

    public function show(Penjualan $penjualan)
    {
        $penjualan->load([
            'customer',
            'user',
            'detailPenjualans.product',
        ]);

        return view('penjualan.show', compact('penjualan'));
    }

    public function destroy(Penjualan $penjualan)
    {
        if (Auth::user()->role !== 'owner') {
            abort(403);
        }

        DB::transaction(function () use ($penjualan) {
            $lockedSale = Penjualan::query()
                ->whereKey($penjualan->id)
                ->lockForUpdate()
                ->firstOrFail();

            $details = $lockedSale->detailPenjualans()
                ->orderBy('product_id')
                ->get();
            $productIds = $details->pluck('product_id')->unique()->sort()->values();
            $products = Product::query()
                ->whereIn('id', $productIds)
                ->orderBy('id')
                ->lockForUpdate()
                ->get()
                ->keyBy('id');

            foreach ($details as $detail) {
                $product = $products->get($detail->product_id);
                if (!$product) {
                    throw ValidationException::withMessages([
                        'sale' => 'Penjualan tidak dapat dibatalkan karena salah satu produk sudah tidak tersedia.',
                    ]);
                }

                $product->stok += $detail->qty;
                $product->save();

                StockMovement::create([
                    'product_id' => $product->id,
                    'jenis' => 'masuk',
                    'qty' => $detail->qty,
                    'keterangan' => 'Pembatalan Penjualan ' . $lockedSale->nomor_penjualan,
                ]);
            }

            $lockedSale->delete();
        });

        return redirect()
            ->route('penjualan.index')
            ->with('success', 'Penjualan dibatalkan dan stok telah dikembalikan.');
    }

    private function generateNextSaleNumber(): string
    {
        $largestNumber = Penjualan::query()
            ->where('nomor_penjualan', 'like', 'TRX%')
            ->pluck('nomor_penjualan')
            ->reduce(function (int $largest, string $number): int {
                if (preg_match('/^TRX(\d+)$/', $number, $matches)) {
                    return max($largest, (int) $matches[1]);
                }

                return $largest;
            }, 0);

        $sequence = $largestNumber + 1;
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
}
