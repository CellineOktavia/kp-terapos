<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;

class StockController extends Controller
{
    public function critical()
    {
        $products = Product::whereColumn(
            'stok',
            '<=',
            'stok_minimum'
        )
            ->with('supplier')
            ->paginate(10);

        return view(
            'stok.kritis',
            compact('products')
        );
    }
}
