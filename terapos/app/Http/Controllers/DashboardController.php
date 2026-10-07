<?php

namespace App\Http\Controllers;

use App\Models\Penjualan;
use App\Services\DashboardDataService;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index(
        Request $request,
        DashboardDataService $dashboardData
    ) {
        /*
        |--------------------------------------------------------------------------
        | FILTER GRAFIK
        |--------------------------------------------------------------------------
        */

        $chartRange = $request->input('chart_range', '7');

        if (!in_array($chartRange, ['today', '7', '30', '365'], true)) {
            $chartRange = '7';
        }

        $salesChartLabels = collect();
        $salesChartValues = collect();


        /*
        |--------------------------------------------------------------------------
        | HARI INI
        |--------------------------------------------------------------------------
        |
        | Gunakan created_at karena field ini menyimpan tanggal + JAM.
        | Field tanggal pada model hanya di-cast sebagai DATE.
        |
        */

        if ($chartRange === 'today') {

            $startDateTime = now()
                ->copy()
                ->startOfDay();

            $endDateTime = now()
                ->copy()
                ->endOfDay();

            $salesData = Penjualan::query()
                ->whereBetween('created_at', [
                    $startDateTime,
                    $endDateTime
                ])
                ->selectRaw('HOUR(created_at) as hour')
                ->selectRaw('SUM(total) as total_sales')
                ->groupByRaw('HOUR(created_at)')
                ->orderBy('hour')
                ->get()
                ->mapWithKeys(function ($item) {

                    return [
                        (int) $item->hour => (float) $item->total_sales
                    ];
                });


            /*
            |--------------------------------------------------------------------------
            | JAM OPERASIONAL 08:00 - 21:00
            |--------------------------------------------------------------------------
            */

            for ($hour = 8; $hour <= 21; $hour++) {

                $salesChartLabels->push(
                    sprintf('%02d:00', $hour)
                );

                $salesChartValues->push(
                    (float) $salesData->get($hour, 0)
                );
            }
        }


        /*
        |--------------------------------------------------------------------------
        | 7 / 30 / 365 HARI
        |--------------------------------------------------------------------------
        |
        | Untuk grafik harian juga gunakan created_at agar konsisten
        | dengan waktu transaksi sebenarnya.
        |
        */ else {

            $days = (int) $chartRange;

            $startDate = now()
                ->copy()
                ->subDays($days - 1)
                ->startOfDay();

            $endDate = now()
                ->copy()
                ->endOfDay();


            $salesData = Penjualan::query()
                ->whereBetween('created_at', [
                    $startDate,
                    $endDate
                ])
                ->selectRaw('DATE(created_at) as date_val')
                ->selectRaw('SUM(total) as total_sales')
                ->groupByRaw('DATE(created_at)')
                ->orderBy('date_val')
                ->get()
                ->mapWithKeys(function ($item) {

                    return [
                        $item->date_val =>
                        (float) $item->total_sales
                    ];
                });


            /*
            |--------------------------------------------------------------------------
            | ISI SEMUA HARI
            |--------------------------------------------------------------------------
            |
            | Jika tidak ada transaksi pada suatu hari,
            | nilainya tetap 0.
            |
            */

            for ($i = $days - 1; $i >= 0; $i--) {

                $date = now()
                    ->copy()
                    ->subDays($i);

                $dateStr = $date->format('Y-m-d');

                $salesChartLabels->push(
                    $date->format('d M')
                );

                $salesChartValues->push(
                    (float) $salesData->get(
                        $dateStr,
                        0
                    )
                );
            }
        }


        /*
        |--------------------------------------------------------------------------
        | AJAX RESPONSE
        |--------------------------------------------------------------------------
        */

        if ($request->ajax()) {

            return response()->json([
                'success' => true,

                'labels' => $salesChartLabels
                    ->values()
                    ->all(),

                'values' => $salesChartValues
                    ->map(function ($value) {
                        return (float) $value;
                    })
                    ->values()
                    ->all(),
            ]);
        }


        /*
        |--------------------------------------------------------------------------
        | DATA DASHBOARD
        |--------------------------------------------------------------------------
        */

        $data = $dashboardData->getDashboardData();

        $data['salesChartLabels'] = $salesChartLabels;

        $data['salesChartValues'] = $salesChartValues;

        $data['chartRange'] = $chartRange;


        return view(
            'dashboard',
            $data
        );
    }
}
