<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Scan extends Model
{
    use HasFactory;

    protected $fillable = [
        'barcode',
        'component',
        'line',
        'user_id',
        'scanner_name'
    ];

    // Prefix Rules Definition
    // HES=hesting, WIF=wifi, RAM=ram, SSD=ssd, UNT=unit
    // SPK=speaker, MOB=mobo, BAT=baterai
    const PREFIX_MAP = [
        'HES' => 'hesting',
        'WIF' => 'wifi',
        'RAM' => 'ram',
        'SSD' => 'ssd',
        'UNT' => 'unit',
        'SPK' => 'speaker',
        'MOB' => 'mobo',
        'BAT' => 'baterai',
    ];

    /**
     * Determine component type from barcode string
     * @param string $barcode
     * @return string|null Returns component name or null if invalid
     */
    public static function determineComponent($barcode)
    {
        $barcode = strtoupper(trim($barcode));
        if (strlen($barcode) < 3)
            return null;

        $prefix = substr($barcode, 0, 3);

        return self::PREFIX_MAP[$prefix] ?? null;
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
