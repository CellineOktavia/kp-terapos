<?php

namespace App\Services;

use App\Models\Penjualan;
use App\Models\PenerimaanBarang;
use App\Models\Product;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class DashboardDataService
{
    public function getDashboardData(?Request $request = null): array
    {
        $now = now();
        $today = $now->copy()->startOfDay();
        $period = (string) ($request?->input('period') ?? 'month');
        $monthStart = $now->copy()->startOfMonth();
        $monthEnd = $now->copy()->endOfMonth();
        if ($request?->filled('start_date') && $request?->filled('end_date')) {
            $periodStart = Carbon::parse($request->input('start_date'))->startOfDay();
            $periodEnd = Carbon::parse($request->input('end_date'))->endOfDay();
        } elseif ($period === 'today') {
            $periodStart = $today;
            $periodEnd = $now->copy()->endOfDay();
        } elseif ($period === 'week') {
            $periodStart = $now->copy()->startOfWeek();
            $periodEnd = $now->copy()->endOfWeek();
        } elseif ($period === 'year') {
            $periodStart = $now->copy()->startOfYear();
            $periodEnd = $now->copy()->endOfYear();
        } else {
            $periodStart = $monthStart;
            $periodEnd = $monthEnd;
            $period = 'month';
        }

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
            ->whereBetween('tanggal', [$periodStart->toDateString(), $periodEnd->toDateString()])
            ->groupByRaw('DATE(tanggal)')
            ->get()
            ->keyBy('sale_date');

        if ($period === 'year') {
            $chartStart = $periodStart->copy();
            $chartDates = collect(range(1, 12))->map(fn (int $month) => $chartStart->copy()->month($month)->startOfMonth());
            $salesRows = Penjualan::query()
                ->whereBetween('tanggal', [$periodStart->toDateString(), $periodEnd->toDateString()])
                ->selectRaw('strftime(\'%m\', tanggal) as period_key')
                ->selectRaw('SUM(total - COALESCE((SELECT SUM(r.refund_amount) FROM penjualan_returns r WHERE r.penjualan_id = penjualans.id), 0)) as total')
                ->groupByRaw('strftime(\'%m\', tanggal)')
                ->get()->keyBy('period_key');
            $salesChartLabels = $chartDates->map(fn (Carbon $date) => $date->locale('id')->translatedFormat('M'))->values();
            $salesChartValues = $chartDates->map(fn (Carbon $date) => (float) ($salesRows->get(str_pad((string) $date->month, 2, '0', STR_PAD_LEFT))->total ?? 0))->values();
        } else {
            $chartDates = collect(CarbonPeriod::create($periodStart->copy()->startOfDay(), $periodEnd->copy()->startOfDay()));
            $salesChartLabels = $chartDates
                ->map(fn (Carbon $date) => $date->locale('id')->translatedFormat('d M'))
                ->values();
            $salesChartValues = $chartDates
                ->map(fn (Carbon $date) => (float) ($salesByDate[$date->toDateString()]->total ?? 0))
                ->values();
        }

        $topProducts = DB::table('detail_penjualans')
            ->join('products', 'detail_penjualans.product_id', '=', 'products.id')
            ->select(
                'products.id',
                'products.nama_produk',
                'products.satuan',
                DB::raw('SUM(detail_penjualans.qty - COALESCE((SELECT SUM(r.qty) FROM detail_penjualan_returns r WHERE r.detail_penjualan_id = detail_penjualans.id), 0)) as qty_terjual')
            )
            ->where('products.aktif', true)
            ->whereBetween('penjualans.tanggal', [$periodStart->toDateString(), $periodEnd->toDateString()])
            ->join('penjualans', 'detail_penjualans.penjualan_id', '=', 'penjualans.id')
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

        $activeProductCount = Product::query()->where('aktif', true)->count();
        $safeStockCount = Product::query()->where('aktif', true)->whereColumn('stok', '>', 'stok_minimum')->count();
        $lowStockCount = Product::query()->where('aktif', true)->where('stok', '>', 0)->whereColumn('stok', '<=', 'stok_minimum')->count();
        $outStockCount = Product::query()->where('aktif', true)->where('stok', 0)->count();

        return [
            'periodLabel' => $periodStart->locale('id')->translatedFormat('d M Y') . ' - ' . $periodEnd->locale('id')->translatedFormat('d M Y'),
            'periodKey' => $period,
            'activeProductCount' => $activeProductCount,
            'safeStockCount' => $safeStockCount,
            'lowStockCount' => $lowStockCount,
            'outStockCount' => $outStockCount,
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
                ->withSum('detailPenerimaanBarangs', 'qty')
                ->orderByDesc('created_at')
                ->limit(5)
                ->get(),
        ];
    }
}
