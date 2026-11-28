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
use Illuminate\Validation\ValidationException;

class ProductController extends Controller
{
    public function autocomplete(Request $request)
    {
        $search = trim((string) $request->input('q', ''));
        $includeInactive = $request->boolean('include_inactive');
        $activeOnly = !$includeInactive;

        $products = Product::query()
            ->with('category:id,nama_kategori')
            ->when($activeOnly, fn ($query) => $query->where('aktif', true))
            ->when($search !== '', function ($query) use ($search) {
                $term = '%' . mb_strtolower($search) . '%';
                $query->where(function ($productQuery) use ($term, $search) {
                    $productQuery->whereRaw('LOWER(kode_produk) LIKE ?', [$term])
                        ->orWhereRaw('LOWER(nama_produk) LIKE ?', [$term])
                        ->orWhereRaw('LOWER(COALESCE(merk, \'\')) LIKE ?', [$term])
                        ->orWhereHas('category', fn ($categoryQuery) =>
                            $categoryQuery->whereRaw('LOWER(nama_kategori) LIKE ?', ['%' . mb_strtolower($search) . '%']));
                });
            })
            ->orderByRaw("CASE WHEN LOWER(kode_produk) = ? THEN 0 WHEN LOWER(kode_produk) LIKE ? THEN 1 WHEN LOWER(nama_produk) LIKE ? THEN 2 ELSE 3 END",
                [mb_strtolower($search), mb_strtolower($search) . '%', mb_strtolower($search) . '%'])
            ->orderBy('nama_produk')
            ->limit(10)
            ->get();

        return response()->json($products->map(fn (Product $product) => [
            'id' => $product->id,
            'code' => $product->kode_produk,
            'name' => $product->nama_produk,
            'merk' => $product->merk,
            'category' => $product->category?->nama_kategori,
            'stock' => (int) $product->stok,
            'buy_price' => (float) $product->harga_beli,
            'sell_price' => (float) $product->harga_jual,
            'unit' => $product->satuan,
            'extra' => implode(' · ', array_filter([
                $product->category?->nama_kategori,
                $product->merk,
                'Stok ' . $product->stok . ' ' . $product->satuan,
            ])),
        ]));
    }
    public function index(Request $request)
    {
        $filters = $request->validate([
            'search' => ['nullable', 'string', 'max:100'],
            'status' => ['nullable', Rule::in(['all', 'active', 'inactive'])],
            'category_id' => ['nullable', 'integer', 'exists:categories,id'],
            'merk' => ['nullable', 'string', 'max:255'],
        ]);

        $products = Product::query()
            ->with('category')
            ->when($filters['category_id'] ?? null, fn ($query, $categoryId) => $query->where('category_id', $categoryId))
            ->when($filters['merk'] ?? null, fn ($query, $merk) => $query->whereRaw('LOWER(merk) = ?', [mb_strtolower($merk)]))
            ->when(($filters['status'] ?? 'active') !== 'all', function ($query) use ($filters) {
                $query->where('aktif', ($filters['status'] ?? 'active') === 'active');
            })
            ->when($filters['search'] ?? null, function ($query, $search) {
                $query->where(function ($productQuery) use ($search) {
                    $term = '%' . mb_strtolower($search) . '%';
                    $productQuery->whereRaw('LOWER(nama_produk) LIKE ?', [$term])
                        ->orWhereRaw('LOWER(kode_produk) LIKE ?', [$term])
                        ->orWhereRaw('LOWER(merk) LIKE ?', [$term])
                        ->orWhereHas('category', function ($categoryQuery) use ($search) {
                            $categoryQuery->whereRaw('LOWER(nama_kategori) LIKE ?', ['%' . mb_strtolower($search) . '%']);
                        });
                });
            })
            ->orderBy('nama_produk')
            ->paginate(25)
            ->withQueryString();

        $categories = Category::query()->orderBy('nama_kategori')->get(['id', 'nama_kategori']);
        $brands = Product::query()
            ->whereNotNull('merk')
            ->where('merk', '<>', '')
            ->select('merk')
            ->distinct()
            ->orderBy('merk')
            ->pluck('merk');

        return view('produk.index', compact('products', 'filters', 'categories', 'brands'));
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
        $existingProducts = Product::query()
            ->with('category:id,nama_kategori')
            ->where('aktif', true)
            ->orderBy('nama_produk')
            ->get(['id', 'category_id', 'kode_produk', 'nama_produk', 'merk', 'satuan', 'harga_jual']);

        return view('produk.create', [
            'categories' => Category::whereHas('products', fn ($query) => $query->where('aktif', true))
                ->orderBy('nama_kategori')
                ->get(),
            'existingProducts' => $existingProducts,
        ]);
    }

    public function store(Request $request)
    {
        $request->merge([
            'kode_dasar' => strtolower(preg_replace('/\s+/', '', (string) $request->input('kode_dasar'))),
            'harga_beli' => $this->normalizePrice($request->input('harga_beli')),
            'harga_jual' => $this->normalizePrice($request->input('harga_jual')),
        ]);

        $validated = $request->validate([
            'kode_dasar' => ['required', 'string', 'max:80', 'regex:/^[a-z0-9]+$/'],
            'nama_produk' => ['required', 'string', 'max:255'],
            'category_id' => ['nullable', 'exists:categories,id'],
            'merk' => ['nullable', 'string', 'max:255'],
            'satuan' => ['required', 'string', 'max:50'],
            'stok_minimum' => ['required', 'integer', 'min:0'],
            'harga_beli' => ['required', 'numeric', 'min:0'],
            'harga_jual' => ['required', 'numeric', 'min:0'],
            'deskripsi' => ['nullable', 'string'],
        ]);

        $duplicateName = Product::query()
            ->whereRaw('LOWER(nama_produk) = ?', [mb_strtolower(trim($validated['nama_produk']))])
            ->first();

        if ($duplicateName) {
            throw ValidationException::withMessages([
                'nama_produk' => "Nama barang sudah digunakan oleh {$duplicateName->kode_produk} ({$duplicateName->nama_produk}).",
            ]);
        }

        $code = $this->codeFromBaseAndPrice($validated['kode_dasar'], $validated['harga_jual']);
        unset($validated['kode_dasar']);

        try {
            DB::transaction(function () use ($validated, $code): void {
                if (Product::query()->where('kode_produk', $code)->exists()) {
                    throw ValidationException::withMessages([
                        'kode_dasar' => 'Kode barang tersebut sudah digunakan untuk harga jual ini.',
                    ]);
                }

                Product::create(array_merge($validated, [
                    'kode_produk' => $code,
                    'stok' => 0,
                    'aktif' => true,
                ]));
            });
        } catch (QueryException $exception) {
            if (!$this->isProductCodeCollision($exception)) {
                throw $exception;
            }

            throw ValidationException::withMessages([
                'kode_dasar' => 'Kode barang tersebut sudah digunakan untuk harga jual ini.',
            ]);
        }

        return redirect()->route('produk.index')
            ->with('success', "Data barang {$code} berhasil disimpan.");
    }

    public function edit(Product $product)
    {
        return view('produk.edit', [
            'product' => $product,
            'categories' => Category::where(function ($query) use ($product) {
                    $query->whereHas('products', fn ($productQuery) => $productQuery->where('aktif', true))
                        ->orWhereKey($product->category_id);
                })
                ->orderBy('nama_kategori')
                ->get(),
            'existingProducts' => Product::query()->with('category:id,nama_kategori')->orderBy('nama_produk')->get(['id','category_id','kode_produk','nama_produk','merk','satuan','harga_beli','harga_jual']),
            'codeBase' => $this->codeBase($product->kode_produk),
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
            'alasan_harga_beli' => ['nullable', 'string', 'max:255'],
            'deskripsi' => ['nullable', 'string'],
        ]);

        $newSalePrice = (float) $validated['harga_jual'];
        $newBuyPrice = (float) $validated['harga_beli'];
        $belowBuyPrice = DB::transaction(function () use ($product, $validated, $newSalePrice, $newBuyPrice) {
            $lockedProduct = Product::query()->whereKey($product->id)->lockForUpdate()->firstOrFail();
            $oldSalePrice = (float) $lockedProduct->harga_jual;
            $oldBuyPrice = (float) $lockedProduct->harga_beli;
            if ($oldSalePrice !== $newSalePrice && !Auth::user()->isOwner()) {
                abort(403, 'Hanya Owner yang dapat mengubah harga jual.');
            }
            if ($oldBuyPrice !== $newBuyPrice && !Auth::user()->isOwner()) {
                abort(403, 'Hanya Owner yang dapat mengubah harga beli.');
            }
            if ($oldBuyPrice !== $newBuyPrice && trim((string) ($validated['alasan_harga_beli'] ?? '')) === '') {
                throw ValidationException::withMessages([
                    'alasan_harga_beli' => 'Alasan perubahan harga beli wajib diisi.',
                ]);
            }

            if ($oldSalePrice !== $newSalePrice) {
                $base = $this->codeBase($lockedProduct->kode_produk);
                $newCode = $this->codeFromBaseAndPrice($base, $newSalePrice);

                if (Product::query()->where('kode_produk', $newCode)->where('id', '<>', $lockedProduct->id)->exists()) {
                    throw ValidationException::withMessages([
                        'harga_jual' => "Kode baru {$newCode} sudah digunakan produk lain.",
                    ]);
                }

                $validated['kode_produk'] = $newCode;
            }

            $lockedProduct->update($validated);
            if ($oldBuyPrice !== $newBuyPrice) {
                ProductPriceHistory::create([
                    'product_id' => $lockedProduct->id,
                    'jenis_harga' => 'beli',
                    'harga_lama' => $oldBuyPrice,
                    'harga_baru' => $newBuyPrice,
                    'user_id' => Auth::id(),
                    'keterangan' => 'Koreksi manual: ' . trim((string) $validated['alasan_harga_beli']),
                ]);
            }
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

            return $newSalePrice < $newBuyPrice;
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
            || $product->priceHistories()->exists()
            || DB::table('cross_subsidy_programs')
                ->where('source_product_id', $product->id)
                ->orWhere('recipient_product_id', $product->id)
                ->exists();

        if ($hasHistory) {
            $product->update(['aktif' => false]);

            return redirect()->route('produk.index')
                ->with('success', 'Barang yang memiliki histori telah dinonaktifkan, bukan dihapus.');
        }

        $product->delete();

        return redirect()->route('produk.index')->with('success', 'Barang berhasil dihapus.');
    }

    private function codeBase(string $code): string
    {
        $separator = strrpos($code, '-');
        $base = $separator === false ? $code : substr($code, 0, $separator);
        $base = strtolower(preg_replace('/\s+/', '', $base));

        if (!preg_match('/^[a-z0-9]+$/', $base)) {
            throw new \RuntimeException('Basis kode produk saat ini tidak dapat digunakan untuk membentuk kode baru.');
        }

        return $base;
    }

    private function codeFromBaseAndPrice(string $base, mixed $price): string
    {
        $cents = (int) round((float) $price * 100);
        $thousands = intdiv($cents, 100000);
        $fraction = $cents % 100000;
        $suffix = (string) $thousands;

        if ($fraction !== 0) {
            $suffix .= '.' . rtrim(str_pad((string) $fraction, 5, '0', STR_PAD_LEFT), '0');
        }

        return $base . '-' . $suffix;
    }

    private function normalizePrice(mixed $value): mixed
    {
        if (!is_string($value)) {
            return $value;
        }

        if (str_contains($value, '-')) {
            return '-' . preg_replace('/\D/', '', $value);
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
