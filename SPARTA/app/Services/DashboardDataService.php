<?php

namespace App\Services;

use App\Models\Faktur;
use App\Models\Penjualan;
use App\Models\Product;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Illuminate\Support\Facades\DB;

class DashboardDataService
{
    public function getDashboardData(): array
    {
        $now = now();
        $today = $now->copy()->startOfDay();
        $monthStart = $now->copy()->startOfMonth();
        $monthEnd = $now->copy()->endOfMonth();
        $chartStart = $today->copy()->subDays(6);
        $chartEnd = $today->copy();

        $dailySales = Penjualan::query()
            ->whereDate('tanggal', $today)
            ->sum('total');

        $monthlySales = Penjualan::query()
            ->whereBetween('tanggal', [$monthStart->toDateString(), $monthEnd->toDateString()])
            ->sum('total');

        $dailyPurchases = Faktur::query()
            ->whereDate('tanggal', $today)
            ->sum('total');

        $monthlyPurchases = Faktur::query()
            ->whereBetween('tanggal', [$monthStart->toDateString(), $monthEnd->toDateString()])
            ->sum('total');

        $salesByDate = Penjualan::query()
            ->selectRaw('DATE(tanggal) as sale_date, SUM(total) as total')
            ->whereBetween('tanggal', [$chartStart->toDateString(), $chartEnd->toDateString()])
            ->groupByRaw('DATE(tanggal)')
            ->get()
            ->keyBy('sale_date');

        $chartDates = collect(CarbonPeriod::create($chartStart, $chartEnd));
        $salesChartLabels = $chartDates
            ->map(fn (Carbon $date) => $date->locale('id')->translatedFormat('d M'))
            ->values();
        $salesChartValues = $chartDates
            ->map(fn (Carbon $date) => (float) ($salesByDate[$date->toDateString()]->total ?? 0))
            ->values();

        $topProducts = DB::table('detail_penjualans')
            ->join('products', 'detail_penjualans.product_id', '=', 'products.id')
            ->select(
                'products.id',
                'products.nama_produk',
                'products.satuan',
                DB::raw('SUM(detail_penjualans.qty) as qty_terjual')
            )
            ->groupBy('products.id', 'products.nama_produk', 'products.satuan')
            ->orderByDesc('qty_terjual')
            ->orderBy('products.nama_produk')
            ->limit(5)
            ->get();

        $criticalProducts = Product::query()
            ->whereColumn('stok', '<=', 'stok_minimum')
            ->orderByRaw('CASE WHEN stok = 0 THEN 0 ELSE 1 END')
            ->orderBy('stok')
            ->orderBy('nama_produk')
            ->limit(5)
            ->get(['id', 'kode_produk', 'nama_produk', 'stok', 'stok_minimum', 'satuan']);

        return [
            'todaySales' => (float) $dailySales,
            'todaySalesCount' => Penjualan::query()->whereDate('tanggal', $today)->count(),
            'todayItemsSold' => (int) DB::table('detail_penjualans')
                ->join('penjualans', 'detail_penjualans.penjualan_id', '=', 'penjualans.id')
                ->whereDate('penjualans.tanggal', $today)
                ->sum('detail_penjualans.qty'),
            'todayPurchases' => (float) $dailyPurchases,
            'todayPurchasesCount' => Faktur::query()->whereDate('tanggal', $today)->count(),
            'criticalStockCount' => Product::query()->whereColumn('stok', '<=', 'stok_minimum')->count(),
            'monthSales' => (float) $monthlySales,
            'monthSalesCount' => Penjualan::query()
                ->whereBetween('tanggal', [$monthStart->toDateString(), $monthEnd->toDateString()])
                ->count(),
            'monthItemsSold' => (int) DB::table('detail_penjualans')
                ->join('penjualans', 'detail_penjualans.penjualan_id', '=', 'penjualans.id')
                ->whereBetween('penjualans.tanggal', [$monthStart->toDateString(), $monthEnd->toDateString()])
                ->sum('detail_penjualans.qty'),
            'monthPurchases' => (float) $monthlyPurchases,
            'monthPurchasesCount' => Faktur::query()
                ->whereBetween('tanggal', [$monthStart->toDateString(), $monthEnd->toDateString()])
                ->count(),
            'salesChartLabels' => $salesChartLabels,
            'salesChartValues' => $salesChartValues,
            'topProducts' => $topProducts,
            'criticalProducts' => $criticalProducts,
            'latestSales' => Penjualan::query()
                ->with('user')
                ->orderByDesc('created_at')
                ->orderByDesc('id')
                ->limit(5)
                ->get(),
            'latestPurchases' => Faktur::query()
                ->with('user')
                ->orderByDesc('created_at')
                ->orderByDesc('id')
                ->limit(5)
                ->get(),
        ];
    }
}
