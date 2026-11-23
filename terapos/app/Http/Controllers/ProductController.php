<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use App\Models\ProductPriceHistory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\QueryException;
use Illuminate\Validation\Rule;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $filters = $request->validate([
            'search' => ['nullable', 'string', 'max:100'],
            'status' => ['nullable', Rule::in(['all', 'active', 'inactive'])],
        ]);

        $products = Product::query()
            ->with('category')
            ->when(($filters['status'] ?? 'active') !== 'all', function ($query) use ($filters) {
                $query->where('aktif', ($filters['status'] ?? 'active') === 'active');
            })
            ->when($filters['search'] ?? null, function ($query, $search) {
                $query->where(function ($productQuery) use ($search) {
                    $productQuery->where('nama_produk', 'like', "%{$search}%")
                        ->orWhere('kode_produk', 'like', "%{$search}%")
                        ->orWhere('merk', 'like', "%{$search}%")
                        ->orWhereHas('category', function ($categoryQuery) use ($search) {
                            $categoryQuery->where('nama_kategori', 'like', "%{$search}%");
                        });
                });
            })
            ->orderBy('nama_produk')
            ->paginate(25)
            ->withQueryString();

        return view('produk.index', ['products' => $products, 'filters' => $filters]);
    }

    public function show(Product $product)
    {
        $product->load([
            'category',
            'priceHistories' => fn ($query) => $query->with('user')->latest(),
            'stockMovements' => fn ($query) => $query->latest()->limit(20),
        ]);

        return view('produk.show', compact('product'));
    }

    public function create()
    {
        return view('produk.create', [
            'categories' => Category::orderBy('nama_kategori')->get(),
            'generatedCode' => $this->generateNextProductCode(),
        ]);
    }

    public function store(Request $request)
    {
        $request->merge([
            'harga_beli' => $this->normalizePrice($request->input('harga_beli')),
            'harga_jual' => $this->normalizePrice($request->input('harga_jual')),
        ]);

        $validated = $request->validate([
            'nama_produk' => ['required', 'string', 'max:255'],
            'category_id' => ['nullable', 'exists:categories,id'],
            'merk' => ['nullable', 'string', 'max:255'],
            'satuan' => ['required', 'string', 'max:50'],
            'stok_minimum' => ['required', 'integer', 'min:0'],
            'harga_beli' => ['required', 'numeric', 'min:0'],
            'harga_jual' => ['required', 'numeric', 'min:0'],
            'deskripsi' => ['nullable', 'string'],
        ]);

        for ($attempt = 1; ; $attempt++) {
            try {
                $code = DB::transaction(function () use ($validated) {
                    $code = $this->generateNextProductCode();
                    Product::create(array_merge($validated, [
                        'kode_produk' => $code,
                        'stok' => 0,
                        'aktif' => true,
                    ]));

                    return $code;
                });
                break;
            } catch (QueryException $exception) {
                if ($attempt >= 5 || !$this->isProductCodeCollision($exception)) {
                    throw $exception;
                }
            }
        }

        return redirect()->route('produk.index')
            ->with('success', "Data barang {$code} berhasil disimpan.");
    }

    public function edit(Product $product)
    {
        return view('produk.edit', [
            'product' => $product,
            'categories' => Category::orderBy('nama_kategori')->get(),
        ]);
    }

    public function update(Request $request, Product $product)
    {
        $request->merge([
            'harga_beli' => $this->normalizePrice($request->input('harga_beli')),
            'harga_jual' => $this->normalizePrice($request->input('harga_jual')),
        ]);

        $validated = $request->validate([
            'category_id' => ['nullable', 'exists:categories,id'],
            'nama_produk' => ['required', 'string', 'max:255'],
            'merk' => ['nullable', 'string', 'max:255'],
            'satuan' => ['required', 'string', 'max:50'],
            'stok_minimum' => ['required', 'integer', 'min:0'],
            'harga_beli' => ['required', 'numeric', 'min:0'],
            'harga_jual' => ['required', 'numeric', 'min:0'],
            'deskripsi' => ['nullable', 'string'],
        ]);

        $newSalePrice = (float) $validated['harga_jual'];
        $belowBuyPrice = DB::transaction(function () use ($product, $validated, $newSalePrice) {
            $lockedProduct = Product::query()->whereKey($product->id)->lockForUpdate()->firstOrFail();
            $oldSalePrice = (float) $lockedProduct->harga_jual;
            $oldBuyPrice = (float) $lockedProduct->harga_beli;
            if ($oldSalePrice !== $newSalePrice && !Auth::user()->isOwner()) {
                abort(403, 'Hanya Owner yang dapat mengubah harga jual.');
            }

            $validated['harga_beli'] = $oldBuyPrice;
            $lockedProduct->update($validated);
            if ($oldSalePrice !== $newSalePrice) {
                ProductPriceHistory::create([
                    'product_id' => $lockedProduct->id,
                    'jenis_harga' => 'jual',
                    'harga_lama' => $oldSalePrice,
                    'harga_baru' => $newSalePrice,
                    'user_id' => Auth::id(),
                    'keterangan' => 'Perubahan harga jual dari Edit Barang',
                ]);
            }

            return $newSalePrice < $oldBuyPrice;
        });

        $warning = $belowBuyPrice
            ? ' Peringatan: harga jual berada di bawah harga beli terakhir. Pastikan harga ini merupakan keputusan Owner/subsidi silang.'
            : '';

        return redirect()->route('produk.show', $product)
            ->with('success', 'Data barang berhasil diperbarui.' . $warning);
    }

    public function toggleStatus(Product $product)
    {
        $product->update(['aktif' => !$product->aktif]);
        $message = $product->aktif ? 'Barang berhasil diaktifkan.' : 'Barang berhasil dinonaktifkan.';

        return redirect()->route('produk.index')->with('success', $message);
    }

    public function destroy(Product $product)
    {
        $hasHistory = $product->detailPenjualans()->exists()
            || DB::table('detail_fakturs')->where('product_id', $product->id)->exists()
            || $product->detailPenerimaanBarangs()->exists()
            || $product->stockMovements()->exists()
            || $product->priceHistories()->exists();

        if ($hasHistory) {
            $product->update(['aktif' => false]);

            return redirect()->route('produk.index')
                ->with('success', 'Barang yang memiliki histori telah dinonaktifkan, bukan dihapus.');
        }

        $product->delete();

        return redirect()->route('produk.index')->with('success', 'Barang berhasil dihapus.');
    }

    private function generateNextProductCode(): string
    {
        $codes = Product::query()->pluck('kode_produk');
        $number = $codes->reduce(function (int $largest, string $code): int {
            return preg_match('/^\d{4}$/', $code) ? max($largest, (int) $code) : $largest;
        }, 0) + 1;

        while ($number <= 9999) {
            $code = str_pad((string) $number, 4, '0', STR_PAD_LEFT);
            if (!$codes->contains($code)) {
                return $code;
            }
            $number++;
        }

        throw new \RuntimeException('Kode barang 4 digit telah habis.');
    }

    private function normalizePrice(mixed $value): mixed
    {
        if (!is_string($value)) {
            return $value;
        }

        return preg_replace('/[^\d]/', '', $value);
    }

    private function isProductCodeCollision(QueryException $exception): bool
    {
        $sqlState = $exception->errorInfo[0] ?? null;
        $message = strtolower($exception->getPrevious()?->getMessage() ?? $exception->getMessage());

        return in_array($sqlState, ['23000', '23505'], true)
            && str_contains($message, 'kode_produk');
    }
}
