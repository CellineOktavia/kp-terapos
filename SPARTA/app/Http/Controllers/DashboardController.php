<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Supplier;
use App\Models\Faktur;
use App\Models\Customer;
use App\Models\Penjualan;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $filter =
            $request->filter
            ?? 'bulan';

        switch ($filter) {

            case 'hari':

                $startDate =
                    now()->toDateString();

                $endDate =
                    now()->toDateString();

                break;

            case 'tahun':

                $startDate =
                    now()->startOfYear()
                    ->toDateString();

                $endDate =
                    now()->endOfYear()
                    ->toDateString();

                break;

            case 'custom':

                $startDate =
                    $request->start_date;

                $endDate =
                    $request->end_date;

                break;

            default:

                $startDate =
                    now()->startOfMonth()
                    ->toDateString();

                $endDate =
                    now()->endOfMonth()
                    ->toDateString();

                break;
        }

        $totalProduk =
            Product::count();

        $totalSupplier =
            Supplier::count();

        $totalCustomer =
            Customer::count();

        $totalPenjualan =
            Penjualan::whereBetween(
                'tanggal',
                [
                    $startDate,
                    $endDate
                ]
            )->sum('total');

        $totalFaktur =
            Faktur::count();

        $stokKritis =
            Product::whereColumn(
                'stok',
                '<=',
                'stok_minimum'
            )->count();

        $pendapatanHarian = Penjualan::select(
            DB::raw(
                'DATE(tanggal) as tanggal'
            ),
            DB::raw(
                'SUM(total) as total'
            )
        )
            ->whereBetween(
                'tanggal',
                [
                    $startDate,
                    $endDate
                ]
            )
            ->groupBy('tanggal')
            ->orderBy('tanggal')
            ->get();

        return view(
            'dashboard.admin',
            compact(
                'totalProduk',
                'totalSupplier',
                'totalCustomer',
                'totalPenjualan',
                'stokKritis',
                'pendapatanHarian',
                'startDate',
                'endDate',
                'totalFaktur',
                'filter'
            )
        );
    }
}
