<?php

namespace App\Http\Controllers;

use App\Models\StockMovement;

class StockMovementController extends Controller
{
    public function index()
    {
        $movements = StockMovement::with(
            'product'
        )
            ->latest()
            ->paginate(15);

        return view(
            'stok.riwayat',
            compact('movements')
        );
    }
}
