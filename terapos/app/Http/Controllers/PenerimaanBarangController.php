<?php

namespace App\Http\Controllers;

use App\Models\DetailPenerimaanBarang;
use App\Models\PenerimaanBarang;
use App\Models\Product;
use App\Models\ProductPriceHistory;
use App\Models\StockMovement;
use App\Models\User;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class PenerimaanBarangController extends Controller
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

        $penerimaans = PenerimaanBarang::query()
            ->with('user')
            ->withCount('detailPenerimaanBarangs')
            ->withSum('detailPenerimaanBarangs', 'qty')
            ->when($filters['search'] ?? null, function ($query, $search) {
                $query->where(function ($receiptQuery) use ($search) {
                    $receiptQuery->where('nomor_penerimaan', 'like', "%{$search}%")
                        ->orWhereHas('user', function ($userQuery) use ($search) {
                            $userQuery->where('name', 'like', "%{$search}%");
                        })
                        ->orWhereHas('detailPenerimaanBarangs.product', function ($productQuery) use ($search) {
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

        $users = User::query()
            ->whereIn('role', ['owner', 'co_owner'])
            ->orderBy('name')
            ->get(['id', 'name']);

        return view('penerimaan.index', compact('penerimaans', 'users', 'filters'));
    }

    public function create()
    {
        $products = Product::query()
            ->with('category:id,nama_kategori')
            ->where('aktif', true)
            ->orderBy('nama_produk')
            ->get(['id', 'category_id', 'kode_produk', 'nama_produk', 'merk', 'satuan', 'harga_beli', 'stok']);

        return view('penerimaan.create', [
            'products' => $products,
            'generatedNumber' => $this->generateNextReceiptNumber(),
        ]);
    }

    public function store(Request $request)
    {
        $items = $request->input('items', []);
        foreach ($items as $index => $item) {
            if (isset($item['harga_beli'])) {
                $items[$index]['harga_beli'] = $this->normalizePrice($item['harga_beli']);
            }
        }
        $request->merge(['items' => $items]);

        $validated = $request->validate([
            'tanggal' => ['required', 'date'],
            'keterangan' => ['nullable', 'string', 'max:1000'],
            'items' => ['required', 'array', 'min:1'],
            'items.*.product_id' => ['required', 'distinct', 'exists:products,id'],
            'items.*.qty' => ['required', 'integer', 'min:1'],
            'items.*.harga_beli' => ['required', 'numeric', 'min:0'],
        ]);

        for ($attempt = 1; ; $attempt++) {
            try {
                [$receiptId, $nomorPenerimaan] = DB::transaction(function () use ($validated) {
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

                    if ($products->count() !== $productIds->count()
                        || $products->contains(fn (Product $product) => !$product->aktif)) {
                        throw ValidationException::withMessages([
                            'items' => 'Salah satu barang tidak lagi aktif atau tersedia.',
                        ]);
                    }

                    $nomorPenerimaan = $this->generateNextReceiptNumber();
                    $penerimaan = PenerimaanBarang::create([
                        'nomor_penerimaan' => $nomorPenerimaan,
                        'user_id' => Auth::id(),
                        'tanggal' => $validated['tanggal'],
                        'keterangan' => $validated['keterangan'] ?? null,
                    ]);

                    foreach ($items as $item) {
                        $product = $products->get((int) $item['product_id']);
                        $qty = (int) $item['qty'];
                        $stockBefore = (int) $product->stok;
                        $oldBuyPrice = (float) $product->harga_beli;
                        $newBuyPrice = (float) $item['harga_beli'];

                        DetailPenerimaanBarang::create([
                            'penerimaan_barang_id' => $penerimaan->id,
                            'product_id' => $product->id,
                            'qty' => $qty,
                            'harga_beli' => $newBuyPrice,
                            'stok_sebelum' => $stockBefore,
                            'stok_sesudah' => $stockBefore + $qty,
                        ]);

                        $product->stok += $qty;
                        $product->harga_beli = $newBuyPrice;
                        $product->save();

                        if ($oldBuyPrice !== $newBuyPrice) {
                            ProductPriceHistory::create([
                                'product_id' => $product->id,
                                'jenis_harga' => 'beli',
                                'harga_lama' => $oldBuyPrice,
                                'harga_baru' => $newBuyPrice,
                                'reference_type' => PenerimaanBarang::class,
                                'reference_id' => $penerimaan->id,
                                'user_id' => Auth::id(),
                                'keterangan' => 'Perubahan harga beli dari Penerimaan Barang ' . $nomorPenerimaan,
                            ]);
                        }

                        StockMovement::create([
                            'product_id' => $product->id,
                            'jenis' => 'masuk',
                            'qty' => $qty,
                            'keterangan' => 'Penerimaan Barang ' . $nomorPenerimaan,
                        ]);
                    }

                    return [$penerimaan->id, $nomorPenerimaan];
                });

                break;
            } catch (QueryException $exception) {
                if ($attempt >= 5 || !$this->isReceiptNumberCollision($exception)) {
                    throw $exception;
                }
            }
        }

        return redirect()
            ->route('penerimaan.show', $receiptId)
            ->with('success', "Penerimaan {$nomorPenerimaan} berhasil. Stok " . count($validated['items']) . ' barang telah diperbarui.');
    }

    public function show(PenerimaanBarang $penerimaanBarang)
    {
        $penerimaanBarang->load([
            'user',
            'detailPenerimaanBarangs.product',
        ]);

        return view('penerimaan.show', ['penerimaan' => $penerimaanBarang]);
    }

    private function generateNextReceiptNumber(): string
    {
        $largestNumber = PenerimaanBarang::query()
            ->pluck('nomor_penerimaan')
            ->reduce(function (int $largest, string $number): int {
                if (preg_match('/^TRM(\d{6})$/', $number, $matches)) {
                    return max($largest, (int) $matches[1]);
                }

                return $largest;
            }, 0);

        $sequence = $largestNumber + 1;
        if ($sequence > 999999) {
            throw new \RuntimeException('Nomor penerimaan telah mencapai batas sequence enam digit.');
        }

        do {
            $number = 'TRM' . str_pad((string) $sequence, 6, '0', STR_PAD_LEFT);
            $sequence++;
        } while (PenerimaanBarang::where('nomor_penerimaan', $number)->exists());

        return $number;
    }

    private function isReceiptNumberCollision(QueryException $exception): bool
    {
        $sqlState = $exception->errorInfo[0] ?? null;
        $message = strtolower(
            $exception->getPrevious()?->getMessage() ?? $exception->getMessage()
        );

        return in_array($sqlState, ['23000', '23505'], true)
            && str_contains($message, 'nomor_penerimaan');
    }

    private function normalizePrice(mixed $value): mixed
    {
        if (!is_string($value)) {
            return $value;
        }

        return preg_replace('/[^\d]/', '', $value);
    }
}
