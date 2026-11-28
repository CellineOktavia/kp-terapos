<?php

namespace App\Http\Controllers;

use App\Services\DashboardDataService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OwnerDashboardController extends Controller
{
    public function index(Request $request, DashboardDataService $dashboardData)
    {
        return view('dashboard.terapos', $dashboardData->getDashboardData($request));
    }

    public function realtime(Request $request, DashboardDataService $dashboardData): JsonResponse
    {
        abort_unless(in_array(Auth::user()?->role, ['owner', 'co_owner'], true), 403);
        $data = $dashboardData->getDashboardData($request);

        return response()->json([
            'todaySales' => (float) ($data['todaySales'] ?? 0),
            'todaySalesCount' => (int) ($data['todaySalesCount'] ?? 0),
            'todayItemsSold' => (int) ($data['todayItemsSold'] ?? 0),
            'criticalStockCount' => (int) ($data['criticalStockCount'] ?? 0),
            'monthSales' => (float) ($data['monthSales'] ?? 0),
            'monthSalesCount' => (int) ($data['monthSalesCount'] ?? 0),
            'labels' => collect($data['salesChartLabels'] ?? [])->values()->all(),
            'values' => collect($data['salesChartValues'] ?? [])->values()->all(),
            'updatedAt' => now()->format('H:i:s'),
        ]);
    }
}
