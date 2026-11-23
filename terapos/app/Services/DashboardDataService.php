<?php

namespace App\Services;

use App\Models\Penjualan;
use App\Models\PenerimaanBarang;
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
            ->selectRaw('COALESCE(SUM(total - COALESCE((SELECT SUM(r.refund_amount) FROM penjualan_returns r WHERE r.penjualan_id = penjualans.id), 0)), 0) as net_total')
            ->value('net_total');

        $monthlySales = Penjualan::query()
            ->whereBetween('tanggal', [$monthStart->toDateString(), $monthEnd->toDateString()])
            ->selectRaw('COALESCE(SUM(total - COALESCE((SELECT SUM(r.refund_amount) FROM penjualan_returns r WHERE r.penjualan_id = penjualans.id), 0)), 0) as net_total')
            ->value('net_total');

        $salesByDate = Penjualan::query()
            ->selectRaw('DATE(tanggal) as sale_date, SUM(total - COALESCE((SELECT SUM(r.refund_amount) FROM penjualan_returns r WHERE r.penjualan_id = penjualans.id), 0)) as total')
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
                DB::raw('SUM(detail_penjualans.qty - COALESCE((SELECT SUM(r.qty) FROM detail_penjualan_returns r WHERE r.detail_penjualan_id = detail_penjualans.id), 0)) as qty_terjual')
            )
            ->groupBy('products.id', 'products.nama_produk', 'products.satuan')
            ->orderByDesc('qty_terjual')
            ->orderBy('products.nama_produk')
            ->limit(5)
            ->get();

        $criticalProducts = Product::query()
            ->where('aktif', true)
            ->whereColumn('stok', '<=', 'stok_minimum')
            ->orderByRaw('CASE WHEN stok = 0 THEN 0 ELSE 1 END')
            ->orderBy('stok')
            ->orderBy('nama_produk')
            ->limit(5)
            ->get(['id', 'kode_produk', 'nama_produk', 'stok', 'stok_minimum', 'satuan']);

        return [
            'todaySales' => (float) $dailySales,
            'todaySalesCount' => Penjualan::query()
                ->whereDate('tanggal', $today)
                ->whereIn('status', ['completed', 'partially_returned'])
                ->count(),
            'todayItemsSold' => (int) DB::table('detail_penjualans')
                ->join('penjualans', 'detail_penjualans.penjualan_id', '=', 'penjualans.id')
                ->whereDate('penjualans.tanggal', $today)
                ->selectRaw('COALESCE(SUM(detail_penjualans.qty - COALESCE((SELECT SUM(r.qty) FROM detail_penjualan_returns r WHERE r.detail_penjualan_id = detail_penjualans.id), 0)), 0) as net_qty')
                ->value('net_qty'),
            'criticalStockCount' => Product::query()->where('aktif', true)
                ->whereColumn('stok', '<=', 'stok_minimum')->count(),
            'monthSales' => (float) $monthlySales,
            'monthSalesCount' => Penjualan::query()
                ->whereBetween('tanggal', [$monthStart->toDateString(), $monthEnd->toDateString()])
                ->whereIn('status', ['completed', 'partially_returned'])
                ->count(),
            'salesChartLabels' => $salesChartLabels,
            'salesChartValues' => $salesChartValues,
            'topProducts' => $topProducts,
            'criticalProducts' => $criticalProducts,
            'latestSales' => Penjualan::query()
                ->with('user')
                ->withSum('returns as returned_amount', 'refund_amount')
                ->orderByDesc('created_at')
                ->orderByDesc('id')
                ->limit(5)
                ->get(),
            'latestReceipts' => PenerimaanBarang::query()
                ->with('user')
                ->withCount('detailPenerimaanBarangs')
                ->orderByDesc('created_at')
                ->limit(5)
                ->get(),
        ];
    }
}
