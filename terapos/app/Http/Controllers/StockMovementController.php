<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\StockMovement;

class StockMovementController extends Controller
{
    public function index(Request $request)
    {
        $validated = $request->validate([
            'search' => ['nullable', 'string', 'max:100'],
            'jenis' => ['nullable', 'in:masuk,keluar'],
            'mulai' => ['nullable', 'date'],
            'sampai' => ['nullable', 'date', 'after_or_equal:mulai'],
        ]);

        $movements = StockMovement::with('product')
            ->when($validated['search'] ?? null, function ($query, $search) {
                $query->where(function ($movementQuery) use ($search) {
                    $movementQuery->where('keterangan', 'like', "%{$search}%")
                        ->orWhereHas('product', function ($productQuery) use ($search) {
                            $productQuery->where('kode_produk', 'like', "%{$search}%")
                                ->orWhere('nama_produk', 'like', "%{$search}%");
                        });
                });
            })
            ->when($validated['jenis'] ?? null, function ($query, $jenis) {
                $query->where('jenis', $jenis);
            })
            ->when($validated['mulai'] ?? null, function ($query, $date) {
                $query->whereDate('created_at', '>=', $date);
            })
            ->when($validated['sampai'] ?? null, function ($query, $date) {
                $query->whereDate('created_at', '<=', $date);
            })
            ->latest()
            ->paginate(15)
            ->withQueryString();

        return view('stok.riwayat', compact('movements'));
    }
}
