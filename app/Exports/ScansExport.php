<?php

namespace App\Exports;

use App\Models\Scan;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class ScansExport implements FromCollection, WithHeadings, WithMapping
{
    protected $line;
    protected $component;
    protected $date;

    public function __construct($line = null, $component = null, $date = null)
    {
        $this->line = $line;
        $this->component = $component;
        $this->date = $date;
    }

    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        $query = Scan::with('user');

        if ($this->line && $this->line !== 'all') {
            $query->where('line', $this->line);
        }

        if ($this->component && $this->component !== 'all') {
            $query->where('component', $this->component);
        }

        if ($this->date) {
            $query->whereDate('created_at', $this->date);
        }

        return $query->latest()->get();
    }

    public function map($scan): array
    {
        return [
            $scan->id,
            $scan->created_at->format('Y-m-d H:i:s'),
            $scan->line,
            strtoupper($scan->component),
            $scan->barcode,
            $scan->user->name ?? 'System',
        ];
    }

    public function headings(): array
    {
        return [
            'ID',
            'Scan Time',
            'Production Line',
            'Component Type',
            'Barcode Number',
            'Scanned By',
        ];
    }
}
