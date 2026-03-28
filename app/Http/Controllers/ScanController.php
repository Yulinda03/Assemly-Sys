<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Scan;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ScanController extends Controller
{
    /**
     * Show the scanning interface.
     */
    public function index()
    {
        // Get recent scans for the view (e.g. today's scans)
        $recentScans = Scan::with('user')
            ->whereDate('created_at', today())
            ->latest()
            ->take(10)
            ->get();

        return view('scan.index', compact('recentScans'));
    }

    /**
     * Handle the barcode submission (AJAX).
     */
    public function store(Request $request)
    {
        $request->validate([
            'barcode' => 'required|string|min:3',
            'line' => 'required|string',
            'scanner_name' => 'required|string'
        ]);

        $barcode = strtoupper(trim($request->barcode));
        $line = $request->line;
        $scanner_name = $request->scanner_name;

        // 1. Anti-Duplicate Check (Global)
        if (Scan::where('barcode', $barcode)->exists()) {
            return response()->json([
                'status' => 'error',
                'message' => "DUPLICATE: Barcode $barcode sudah pernah discan!"
            ], 422);
        }

        // 2. Auto-Routing (Prefix Check)
        $component = Scan::determineComponent($barcode);

        if (!$component) {
            return response()->json([
                'status' => 'error',
                'message' => "INVALID: Prefix barcode tidak dikenali ($barcode)."
            ], 422);
        }

        // 3. Save to Database
        try {
            $scan = Scan::create([
                'barcode' => $barcode,
                'component' => $component,
                'line' => $line,
                'user_id' => Auth::id() ?? 1, // Fallback to 1 if testing without login
                'scanner_name' => $scanner_name,
            ]);

            return response()->json([
                'status' => 'success',
                'message' => "OK: $component ($barcode) disimpan ke $line",
                'data' => $scan
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'System Error: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get recent scans for a specific line (AJAX).
     */
    public function history(Request $request)
    {
        $line = $request->query('line');

        $query = Scan::whereDate('created_at', today());

        if ($line) {
            $query->where('line', $line);
        }

        $recentScans = $query->latest()
            ->take(20) // Limit to 20 for performance
            ->get();

        return response()->json([
            'status' => 'success',
            'data' => $recentScans
        ]);
    }
}
