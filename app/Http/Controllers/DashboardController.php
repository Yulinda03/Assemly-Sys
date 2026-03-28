<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Scan;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        // Stats: Total Scans Per Component
        $componentStats = Scan::select('component', DB::raw('count(*) as total'))
            ->groupBy('component')
            ->pluck('total', 'component')
            ->all();

        // Stats: Total Scans Per Line
        $lineStats = Scan::select('line', DB::raw('count(*) as total'))
            ->groupBy('line')
            ->orderByRaw('CAST(SUBSTRING(line, 6) AS UNSIGNED)') // Sort by Line Number
            ->get();

        // Prepare default components list to ensure 0 values show up
        $allComponents = Scan::PREFIX_MAP;
        $stats = [];
        foreach ($allComponents as $prefix => $name) {
            $stats[$name] = $componentStats[$name] ?? 0;
        }

        // Recent Activity
        $recentScans = Scan::with('user')->latest()->take(5)->get();

        return view('dashboard', compact('stats', 'lineStats', 'recentScans'));
    }
}
