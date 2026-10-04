<?php

namespace App\Http\Controllers;

use App\Models\Faktur;
use App\Models\Product;
use App\Models\Supplier;
use App\Models\DetailFaktur;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\StockMovement;

class FakturController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->search;

        $fakturs = Faktur::query()

            ->when($search, function ($query) use ($search) {

                $query->where(
                    'nomor_faktur',
                    'like',
                    "%{$search}%"
                );
            })

            ->latest()

            ->paginate(10)

            ->withQueryString();

        return view(
            'faktur.index',
            compact(
                'fakturs',
                'search'
            )
        );
    }

    public function create()
    {
        $suppliers = Supplier::all();

        $products = Product::all();

        return view(
            'faktur.create',
            compact(
                'suppliers',
                'products'
            )
        );
    }

    public function store(Request $request)
    {
        $request->validate([

            'supplier_id' => 'required|exists:suppliers,id',

            'product_id' => 'required|exists:products,id',

            'qty' => 'required|integer|min:1',

            'harga' => 'required|numeric|min:1',

            'tanggal' => 'required|date',

        ]);

        $subtotal =
            $request->qty *
            $request->harga;

        $faktur = Faktur::create([

            'nomor_faktur' =>
            'INV-' . now()->format('YmdHis'),

            'user_id' =>
            Auth::id(),

            'supplier_id' =>
            $request->supplier_id,

            'tanggal' =>
            $request->tanggal,

            'total' =>
            $subtotal,

        ]);

        DetailFaktur::create([

            'faktur_id' =>
            $faktur->id,

            'product_id' =>
            $request->product_id,

            'qty' =>
            $request->qty,

            'harga' =>
            $request->harga,

            'subtotal' =>
            $subtotal,

        ]);

        $product = Product::findOrFail(
            $request->product_id
        );

        $product->increment(
            'stok',
            $request->qty
        );

        StockMovement::create([

            'product_id' =>
            $product->id,

            'jenis' =>
            'masuk',

            'qty' =>
            $request->qty,

            'keterangan' =>
            'Faktur ' .
                $faktur->nomor_faktur,

        ]);

        return redirect()
            ->route('faktur.index')
            ->with(
                'success',
                'Faktur berhasil dibuat'
            );
    }

    public function show(Faktur $faktur)
    {
        $faktur->load([

            'supplier',

            'user',

            'detailFakturs.product'

        ]);

        return view(
            'faktur.show',
            compact('faktur')
        );
    }

    public function destroy(Faktur $faktur)
    {
        if (Auth::user()->role !== 'owner') {

            abort(403);
        }
        foreach (
            $faktur->detailFakturs
            as $detail
        ) {

            if ($detail->product) {

                $detail->product->decrement(
                    'stok',
                    $detail->qty
                );

                StockMovement::create([

                    'product_id' =>
                    $detail->product->id,

                    'jenis' =>
                    'keluar',

                    'qty' =>
                    $detail->qty,

                    'keterangan' =>
                    'Pembatalan Faktur ' .
                        $faktur->nomor_faktur,

                ]);
            }
        }

        $faktur->delete();

        return redirect()
            ->route('faktur.index')
            ->with(
                'success',
                'Faktur berhasil dihapus'
            );
    }

    public function edit(Faktur $faktur)
    {
        if (Auth::user()->role !== 'owner') {
            abort(403);
        }

        $suppliers = Supplier::all();
        $products = Product::all();

        $faktur->load('detailFakturs');

        $detail = $faktur->detailFakturs->first();

        return view(
            'faktur.edit',
            compact(
                'faktur',
                'suppliers',
                'products',
                'detail'
            )
        );
    }

    public function update(Request $request, Faktur $faktur)
{
    if (Auth::user()->role !== 'owner') {
        abort(403);
    }

    $request->validate([
        'supplier_id' => 'required|exists:suppliers,id',
        'product_id' => 'required|exists:products,id',
        'qty' => 'required|integer|min:1',
        'harga' => 'required|numeric|min:1',
        'tanggal' => 'required|date',
    ]);

    DB::transaction(function () use ($request, $faktur) {

        $detail = $faktur->detailFakturs()->first();

        $subtotal = $request->qty * $request->harga;

        $faktur->update([
            'supplier_id' => $request->supplier_id,
            'tanggal' => $request->tanggal,
            'total' => $subtotal,
        ]);

        if ($detail) {
            $oldProduct = Product::find($detail->product_id);
            $newProduct = Product::findOrFail($request->product_id);

            if ($oldProduct && $oldProduct->id != $newProduct->id) {
                $oldProduct->decrement('stok', $detail->qty);
                $newProduct->increment('stok', $request->qty);
            } else {
                $selisihQty = $request->qty - $detail->qty;

                if ($selisihQty > 0) {
                    $newProduct->increment('stok', $selisihQty);
                } elseif ($selisihQty < 0) {
                    $newProduct->decrement('stok', abs($selisihQty));
                }
            }

            $detail->update([
                'product_id' => $request->product_id,
                'qty' => $request->qty,
                'harga' => $request->harga,
                'subtotal' => $subtotal,
            ]);
        } else {
            DetailFaktur::create([
                'faktur_id' => $faktur->id,
                'product_id' => $request->product_id,
                'qty' => $request->qty,
                'harga' => $request->harga,
                'subtotal' => $subtotal,
            ]);

            $product = Product::findOrFail($request->product_id);
            $product->increment('stok', $request->qty);
        }

        StockMovement::create([
            'product_id' => $request->product_id,
            'jenis' => 'masuk',
            'qty' => $request->qty,
            'keterangan' => 'Edit Faktur ' . $faktur->nomor_faktur,
        ]);
    });

    return redirect()
        ->route('faktur.index')
        ->with('success', 'Faktur berhasil diperbarui');
}
}