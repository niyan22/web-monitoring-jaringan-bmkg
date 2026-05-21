<?php

namespace App\Http\Controllers;

use App\Models\SystemMetric;
use App\Models\NetworkTraffic;

class DashboardController extends Controller
{
    public function index()
    {
        // Data sistem terbaru
        $latestSystem = SystemMetric::latest('recorded_at')->first();

        // Data network terbaru
        $latestNetwork = NetworkTraffic::latest('recorded_at')->first();

        // Chart traffic: 20 data terakhir (untuk grafik line di dashboard)
        $chartRecords = NetworkTraffic::orderBy('recorded_at', 'desc')
            ->limit(20)
            ->get()
            ->reverse()
            ->values();

        $chartLabels   = $chartRecords->map(fn($r) => $r->recorded_at->format('H:i'))->toArray();
        $chartDownload = $chartRecords->pluck('download_speed')->toArray();
        $chartUpload   = $chartRecords->pluck('upload_speed')->toArray();

        return view('dashboard.index', compact(
            'latestSystem',
            'latestNetwork',
            'chartLabels',
            'chartDownload',
            'chartUpload'
        ));
    }
}
