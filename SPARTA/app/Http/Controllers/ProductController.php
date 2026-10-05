<?php

namespace App\Http\Controllers;

use App\Models\Supplier;
use App\Models\Product;
use App\Models\Category;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->search;

        $products = Product::query()
            ->with('category')
            ->when($search, function ($query) use ($search) {
                $query->where(function ($q) use ($search) {
                    $q->where('nama_produk', 'like', "%{$search}%")
                        ->orWhere('kode_produk', 'like', "%{$search}%")
                        ->orWhere('barcode', 'like', "%{$search}%")
                        ->orWhere('merk', 'like', "%{$search}%")
                        ->orWhereHas('category', function ($categoryQuery) use ($search) {
                            $categoryQuery->where('nama_kategori', 'like', "%{$search}%");
                        });
                });
            })
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view('produk.index', compact('products', 'search'));
    }

    public function show(Product $product)
    {
        return view('produk.show', compact('product'));
    }

    public function create()
    {
        $suppliers = Supplier::all();
        $categories = Category::orderBy('nama_kategori')->get();

        return view('produk.create', [
            'suppliers' => $suppliers,
            'categories' => $categories,
            'generatedCode' => $this->generateNextProductCode(),
            'generatedBarcode' => $this->generateNextBarcode(),
        ]);
    }

    public function store(Request $request)
    {
        $rules = [
            'kode_produk' => ['required', 'string', Rule::unique('products', 'kode_produk')],
            'barcode' => ['required', 'string', Rule::unique('products', 'barcode')],
            'category_id' => 'nullable|exists:categories,id',
            'supplier_id' => 'nullable|exists:suppliers,id',
            'nama_produk' => 'required|string',
            'merk' => 'nullable|string',
            'satuan' => 'required|string',
            'stok' => 'required|integer|min:0',
            'stok_minimum' => 'required|integer|min:0',
            'harga_beli' => 'required|numeric|min:0',
            'harga_jual' => 'required|numeric|min:0',
            'deskripsi' => 'nullable|string',
        ];

        for ($attempt = 1; ; $attempt++) {
            try {
                DB::transaction(function () use ($request, $rules) {
                    $identity = [
                        'kode_produk' => $this->generateNextProductCode(),
                        'barcode' => $this->generateNextBarcode(),
                    ];
                    $input = array_merge(
                        $request->except(['kode_produk', 'barcode']),
                        $identity
                    );
                    $validated = Validator::make($input, $rules)->validate();

                    Product::create($validated);
                });

                break;
            } catch (QueryException $exception) {
                if ($attempt >= 5 || !$this->isProductIdentityCollision($exception)) {
                    throw $exception;
                }
            } catch (ValidationException $exception) {
                $errors = $exception->errors();
                $identityCollision = isset($errors['kode_produk']) || isset($errors['barcode']);

                if ($attempt >= 5 || !$identityCollision) {
                    throw $exception;
                }
            }
        }

        return redirect()
            ->route('produk.index')
            ->with('success', 'Produk berhasil ditambahkan');
    }

    public function edit(Product $product)
    {
        $suppliers = Supplier::all();
        $categories = Category::orderBy('nama_kategori')->get();

        return view('produk.edit', compact('product', 'suppliers', 'categories'));
    }

    public function update(Request $request, Product $product)
    {
        $validated = $request->validate([
            'category_id' => 'nullable|exists:categories,id',
            'supplier_id' => 'nullable|exists:suppliers,id',
            'nama_produk' => 'required|string|max:255',
            'merk' => 'nullable|string|max:255',
            'satuan' => 'required|string|max:50',
            'stok' => 'required|integer|min:0',
            'stok_minimum' => 'required|integer|min:0',
            'harga_beli' => 'required|numeric|min:0',
            'harga_jual' => 'required|numeric|min:0',
            'deskripsi' => 'nullable|string',
        ]);

        $product->update($validated);

        return redirect()
            ->route('produk.index')
            ->with('success', 'Produk berhasil diperbarui');
    }

    public function destroy(Product $product)
    {
        $product->delete();

        return redirect()
            ->route('produk.index')
            ->with('success', 'Produk berhasil dihapus');
    }

    private function generateNextProductCode(): string
    {
        $largestNumber = Product::query()
            ->where('kode_produk', 'like', 'TERA%')
            ->pluck('kode_produk')
            ->reduce(function (int $largest, string $code): int {
                if (preg_match('/^TERA(\d+)$/', $code, $matches)) {
                    return max($largest, (int) $matches[1]);
                }

                return $largest;
            }, 0);

        $number = $largestNumber + 1;
        do {
            $code = 'TERA' . str_pad((string) $number, 4, '0', STR_PAD_LEFT);
            $number++;
        } while (Product::where('kode_produk', $code)->exists());

        return $code;
    }

    private function generateNextBarcode(): string
    {
        $prefix = 200000000000;
        $largestSequence = Product::query()
            ->where('barcode', 'like', '200%')
            ->whereNotNull('barcode')
            ->pluck('barcode')
            ->reduce(function (int $largest, string $barcode) use ($prefix): int {
                if (!preg_match('/^\d{13}$/', $barcode)) {
                    return $largest;
                }

                $body = substr($barcode, 0, 12);
                if (!$this->hasValidEan13CheckDigit($barcode) || (int) $body <= $prefix) {
                    return $largest;
                }

                return max($largest, (int) $body - $prefix);
            }, 0);

        $sequence = $largestSequence + 1;
        do {
            $body = str_pad((string) ($prefix + $sequence), 12, '0', STR_PAD_LEFT);
            $barcode = $body . $this->ean13CheckDigit($body);
            $sequence++;
        } while (Product::where('barcode', $barcode)->exists());

        return $barcode;
    }

    private function ean13CheckDigit(string $body): string
    {
        $sum = 0;
        foreach (str_split($body) as $index => $digit) {
            $sum += (int) $digit * ($index % 2 === 0 ? 1 : 3);
        }

        return (string) ((10 - ($sum % 10)) % 10);
    }

    private function hasValidEan13CheckDigit(string $barcode): bool
    {
        return substr($barcode, -1) === $this->ean13CheckDigit(substr($barcode, 0, 12));
    }

    private function isProductIdentityCollision(QueryException $exception): bool
    {
        $sqlState = $exception->errorInfo[0] ?? null;
        $databaseMessage = strtolower($exception->getPrevious()?->getMessage() ?? $exception->getMessage());

        return in_array($sqlState, ['23000', '23505'], true)
            && (str_contains($databaseMessage, 'kode_produk') || str_contains($databaseMessage, 'barcode'));
    }
}
