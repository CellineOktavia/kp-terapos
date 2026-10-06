<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\StockMovement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class StockController extends Controller
{
    public function index(Request $request)
    {
        $validated = $request->validate([
            'search' => ['nullable', 'string', 'max:100'],
            'status' => ['nullable', 'in:semua,aman,menipis,habis'],
        ]);

        $products = Product::query()
            ->with('category')
            ->when($validated['search'] ?? null, function ($query, $search) {
                $query->where(function ($productQuery) use ($search) {
                    $productQuery->where('kode_produk', 'like', "%{$search}%")
                        ->orWhere('barcode', 'like', "%{$search}%")
                        ->orWhere('nama_produk', 'like', "%{$search}%");
                });
            })
            ->when(($validated['status'] ?? 'semua') !== 'semua', function ($query) use ($validated) {
                if ($validated['status'] === 'habis') {
                    $query->where('stok', 0);
                } elseif ($validated['status'] === 'menipis') {
                    $query->where('stok', '>', 0)
                        ->whereColumn('stok', '<=', 'stok_minimum');
                } elseif ($validated['status'] === 'aman') {
                    $query->whereColumn('stok', '>', 'stok_minimum');
                }
            })
            ->orderBy('nama_produk')
            ->paginate(15)
            ->withQueryString();

        return view('stok.index', compact('products'));
    }

    public function critical(Request $request)
    {
        $validated = $request->validate([
            'search' => ['nullable', 'string', 'max:100'],
        ]);

        $products = Product::query()
            ->with('category')
            ->whereColumn('stok', '<=', 'stok_minimum')
            ->when($validated['search'] ?? null, function ($query, $search) {
                $query->where(function ($productQuery) use ($search) {
                    $productQuery->where('kode_produk', 'like', "%{$search}%")
                        ->orWhere('barcode', 'like', "%{$search}%")
                        ->orWhere('nama_produk', 'like', "%{$search}%");
                });
            })
            ->orderByRaw('CASE WHEN stok = 0 THEN 0 ELSE 1 END')
            ->orderBy('stok')
            ->orderBy('nama_produk')
            ->paginate(15)
            ->withQueryString();

        return view('stok.kritis', compact('products'));
    }

    public function createAdjustment()
    {
        $products = Product::orderBy('nama_produk')->get();

        return view('stok.adjustment', compact('products'));
    }

    public function storeAdjustment(Request $request)
    {
        $validated = $request->validate([
            'product_id' => ['required', 'exists:products,id'],
            'jenis' => ['required', 'in:masuk,keluar'],
            'qty' => ['required', 'integer', 'min:1'],
            'alasan' => ['required', 'string', 'max:200'],
        ]);

        DB::transaction(function () use ($validated) {
            $product = Product::query()
                ->whereKey($validated['product_id'])
                ->lockForUpdate()
                ->firstOrFail();

            if ($validated['jenis'] === 'keluar' && $validated['qty'] > $product->stok) {
                throw ValidationException::withMessages([
                    'qty' => "Stok {$product->nama_produk} tidak mencukupi. Stok tersedia: {$product->stok}.",
                ]);
            }

            if ($validated['jenis'] === 'masuk') {
                $product->stok += $validated['qty'];
            } else {
                $product->stok -= $validated['qty'];
            }

            $product->save();

            StockMovement::create([
                'product_id' => $product->id,
                'jenis' => $validated['jenis'],
                'qty' => $validated['qty'],
                'keterangan' => 'Penyesuaian stok: ' . $validated['alasan'],
            ]);
        });

        return redirect()
            ->route('stok.index')
            ->with('success', 'Penyesuaian stok berhasil disimpan.');
    }
}
