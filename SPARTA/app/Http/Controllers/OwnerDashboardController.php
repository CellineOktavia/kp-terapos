<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Supplier;
use App\Models\Customer;
use App\Models\Penjualan;
use App\Models\Faktur;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OwnerDashboardController extends Controller
{
    public function index(Request $request)
    {
        // ==================================
        // FILTER TANGGAL
        // ==================================

        $filter = $request->get('filter', 'tahun');

        if ($filter == 'hari') {

            $startDate = now()->toDateString();
            $endDate   = now()->toDateString();
        } elseif ($filter == 'bulan') {

            $startDate = now()->startOfMonth()->toDateString();
            $endDate   = now()->endOfMonth()->toDateString();
        } elseif ($filter == 'custom') {

            $startDate = $request->start_date
                ?? now()->startOfMonth()->toDateString();

            $endDate = $request->end_date
                ?? now()->toDateString();
        } else {

            $startDate = now()->startOfYear()->toDateString();
            $endDate   = now()->endOfYear()->toDateString();
        }

        // ==================================
        // KPI
        // ==================================

        $totalProduk   = Product::count();
        $totalSupplier = Supplier::count();
        $totalCustomer = Customer::count();

        $totalPenjualan = Penjualan::whereBetween(
            'tanggal',
            [$startDate, $endDate]
        )->sum('total');

        $totalPembelian = Faktur::whereBetween(
            'tanggal',
            [$startDate, $endDate]
        )->sum('total');

        $profit = $totalPenjualan - $totalPembelian;

        // ==================================
        // DATA GRAFIK
        // ==================================

        $penjualanHarian = Penjualan::whereBetween(
            'tanggal',
            [$startDate, $endDate]
        )
            ->select(
                'tanggal',
                DB::raw('SUM(total) as pendapatan')
            )
            ->groupBy('tanggal')
            ->orderBy('tanggal')
            ->get()
            ->keyBy('tanggal');

        $pembelianHarian = Faktur::whereBetween(
            'tanggal',
            [$startDate, $endDate]
        )
            ->select(
                'tanggal',
                DB::raw('SUM(total) as pengeluaran')
            )
            ->groupBy('tanggal')
            ->orderBy('tanggal')
            ->get()
            ->keyBy('tanggal');

        $tanggalGabungan = collect(
            array_unique(
                array_merge(
                    $penjualanHarian->keys()->toArray(),
                    $pembelianHarian->keys()->toArray()
                )
            )
        )->sort()->values();

        $dataHarian = $tanggalGabungan->map(function ($tanggal) use (
            $penjualanHarian,
            $pembelianHarian
        ) {

            return (object) [

                'tanggal' => $tanggal,

                'pendapatan' =>
                $penjualanHarian[$tanggal]->pendapatan ?? 0,

                'pengeluaran' =>
                $pembelianHarian[$tanggal]->pengeluaran ?? 0,

            ];
        });

        // ==================================
        // TOP PRODUK
        // ==================================

        $topProduk = DB::table('detail_penjualans')
            ->join(
                'products',
                'detail_penjualans.product_id',
                '=',
                'products.id'
            )
            ->join(
                'penjualans',
                'detail_penjualans.penjualan_id',
                '=',
                'penjualans.id'
            )
            ->whereBetween(
                'penjualans.tanggal',
                [$startDate, $endDate]
            )
            ->select(
                'products.nama_produk',
                DB::raw(
                    'SUM(detail_penjualans.qty) as total_terjual'
                ),
                DB::raw(
                    'SUM(detail_penjualans.subtotal) as total_pendapatan'
                )
            )
            ->groupBy(
                'products.id',
                'products.nama_produk'
            )
            ->orderByDesc('total_terjual')
            ->limit(5)
            ->get();

        $totalProdukTerjual = DB::table('detail_penjualans')
            ->join(
                'penjualans',
                'detail_penjualans.penjualan_id',
                '=',
                'penjualans.id'
            )
            ->whereBetween(
                'penjualans.tanggal',
                [$startDate, $endDate]
            )
            ->sum('detail_penjualans.qty');

        // ==================================
        // TOP CUSTOMER
        // ==================================

        $topCustomer = DB::table('penjualans')
            ->join(
                'customers',
                'penjualans.customer_id',
                '=',
                'customers.id'
            )
            ->whereBetween(
                'penjualans.tanggal',
                [$startDate, $endDate]
            )
            ->select(
                'customers.nama_customer',
                DB::raw(
                    'SUM(penjualans.total) as total_belanja'
                )
            )
            ->groupBy(
                'customers.id',
                'customers.nama_customer'
            )
            ->orderByDesc('total_belanja')
            ->limit(5)
            ->get();

        // ==================================
        // STOK KRITIS
        // ==================================

        $stokKritis = Product::whereColumn(
            'stok',
            '<=',
            'stok_minimum'
        )->get();

        $totalStokKritis = $stokKritis->count();

        // ==================================
        // VIEW
        // ==================================

        return view(
            'dashboard.owner',
            compact(
                'filter',
                'startDate',
                'endDate',
                'dataHarian',
                'totalProduk',
                'totalSupplier',
                'totalCustomer',
                'totalPenjualan',
                'totalPembelian',
                'profit',
                'topProduk',
                'topCustomer',
                'stokKritis',
                'totalProdukTerjual',
                'totalStokKritis'
            )
        );
    }
}
