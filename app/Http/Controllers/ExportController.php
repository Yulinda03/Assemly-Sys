<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Exports\ScansExport;
use Maatwebsite\Excel\Facades\Excel;
use App\Models\Scan;

class ExportController extends Controller
{
    public function index()
    {
        return view('export.index');
    }

    public function download(Request $request)
    {
        $line = $request->input('line');
        $component = $request->input('component');
        $date = $request->input('date');

        $fileName = 'assembly_scans_' . date('Y-m-d_H-i') . '.xlsx';

        return Excel::download(new ScansExport($line, $component, $date), $fileName);
    }
}
