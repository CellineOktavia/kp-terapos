<?php

namespace App\Http\Controllers;

use App\Services\DashboardDataService;

class OwnerDashboardController extends Controller
{
    public function index(DashboardDataService $dashboardData)
    {
        return view('dashboard.terapos', $dashboardData->getDashboardData());
    }
}
