<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Penjualan;
use App\Models\DetailPenjualan;
use App\Models\Product;
use App\Models\Customer;
use App\Models\StockMovement;
use Illuminate\Support\Facades\Auth;

class PenjualanController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->search;

        $penjualans = Penjualan::with('customer')

            ->when($search, function ($query) use ($search) {

                $query->where(
                    'nomor_penjualan',
                    'like',
                    "%{$search}%"
                );
            })

            ->latest()

            ->paginate(10)

            ->withQueryString();

        return view(
            'penjualan.index',
            compact(
                'penjualans',
                'search'
            )
        );
    }
    public function create()
    {
        $customers = Customer::all();

        $products = Product::all();

        return view(
            'penjualan.create',
            compact(
                'customers',
                'products'
            )
        );
    }

    public function store(Request $request)
    {
        $request->validate([

            'customer_id' =>
            'required',

            'product_id' =>
            'required',

            'qty' =>
            'required|integer|min:1',

            'tanggal' =>
            'required',

        ]);

        $product = Product::findOrFail(
            $request->product_id
        );

        if (
            $product->stok <
            $request->qty
        ) {

            return back()
                ->with(
                    'error',
                    'Stok tidak mencukupi'
                );
        }

        $subtotal =
            $request->qty *
            $product->harga_jual;

        $penjualan = Penjualan::create([

            'nomor_penjualan' =>
            'PJ-' .
                now()->format('YmdHis'),

            'customer_id' =>
            $request->customer_id,

            'user_id' =>
            Auth::id(),

            'tanggal' =>
            $request->tanggal,

            'total' =>
            $subtotal

        ]);

        DetailPenjualan::create([

            'penjualan_id' =>
            $penjualan->id,

            'product_id' =>
            $product->id,

            'qty' =>
            $request->qty,

            'harga' =>
            $product->harga_jual,

            'subtotal' =>
            $subtotal

        ]);

        $product->decrement(
            'stok',
            $request->qty
        );

        // Kurangi stok otomatis
        StockMovement::create([

            'product_id' =>
            $product->id,

            'jenis' =>
            'keluar',

            'qty' =>
            $request->qty,

            'keterangan' =>
            'Penjualan ' .
                $penjualan->nomor_penjualan,

        ]);

        return redirect()
            ->route(
                'penjualan.index'
            )
            ->with(
                'success',
                'Penjualan berhasil dibuat'
            );
    }

    public function show(
        Penjualan $penjualan
    ) {
        $penjualan->load([
            'customer',
            'detailPenjualans.product'
        ]);

        return view(
            'penjualan.show',
            compact('penjualan')
        );
    }

    public function edit(
        Penjualan $penjualan
    ) {
        if (
            Auth::user()->role !== 'owner'
        ) {
            abort(403);
        }

        $customers =
            Customer::all();

        $products =
            Product::all();

        $detail = $penjualan
            ->detailPenjualans
            ->first();

        if (!$detail) {

            return redirect()
                ->route('penjualan.index')
                ->with(
                    'error',
                    'Detail penjualan tidak ditemukan'
                );
        }
        return view(
            'penjualan.edit',
            compact(
                'penjualan',
                'customers',
                'products',
                'detail'
            )
        );
    }

    public function update(
        Request $request,
        Penjualan $penjualan
    ) {
        if (
            Auth::user()->role !== 'owner'
        ) {
            abort(403);
        }

        $request->validate([

            'customer_id' =>
            'required',

            'product_id' =>
            'required',

            'qty' =>
            'required|integer|min:1',

            'tanggal' =>
            'required'

        ]);

        $detail =
            $penjualan
            ->detailPenjualans
            ->first();

        $oldProduct =
            Product::findOrFail(
                $detail->product_id
            );

        /*
     * Kembalikan stok lama
     */
        $oldProduct->increment(
            'stok',
            $detail->qty
        );

        $newProduct =
            Product::findOrFail(
                $request->product_id
            );

        if (
            $newProduct->stok <
            $request->qty
        ) {

            return back()
                ->with(
                    'error',
                    'Stok tidak cukup'
                );
        }

        /*
     * Kurangi stok baru
     */
        $newProduct->decrement(
            'stok',
            $request->qty
        );

        $subtotal =
            $request->qty *
            $newProduct->harga_jual;

        $penjualan->update([

            'customer_id' =>
            $request->customer_id,

            'tanggal' =>
            $request->tanggal,

            'total' =>
            $subtotal

        ]);

        $detail->update([

            'product_id' =>
            $request->product_id,

            'qty' =>
            $request->qty,

            'harga' =>
            $newProduct->harga_jual,

            'subtotal' =>
            $subtotal

        ]);

        return redirect()
            ->route(
                'penjualan.index'
            )
            ->with(
                'success',
                'Penjualan berhasil diperbarui'
            );
    }

    public function destroy(
        Penjualan $penjualan
    ) {
        if (
            Auth::user()->role !== 'owner'
        ) {

            abort(403);
        }

        foreach (
            $penjualan->detailPenjualans
            as $detail
        ) {

            if ($detail->product) {

                $detail->product->increment(
                    'stok',
                    $detail->qty
                );

                StockMovement::create([

                    'product_id' =>
                    $detail->product->id,

                    'jenis' =>
                    'masuk',

                    'qty' =>
                    $detail->qty,

                    'keterangan' =>
                    'Pembatalan Penjualan ' .
                        $penjualan->nomor_penjualan,

                ]);
            }
        }

        $penjualan->delete();

        return redirect()
            ->route('penjualan.index')
            ->with(
                'success',
                'Penjualan berhasil dihapus'
            );
    }
}
