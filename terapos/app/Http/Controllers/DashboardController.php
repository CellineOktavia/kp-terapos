<?php

namespace App\Http\Controllers;

use App\Services\DashboardDataService;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index(Request $request, DashboardDataService $dashboardData)
    {
        return view('dashboard.terapos', $dashboardData->getDashboardData($request));
    }
}
