<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Periode extends Model
{
    protected $table = 'periode';

    protected $fillable = [
        'nama',
        'aktif',
        'ditutup'
    ];

    protected $casts = [
        'aktif' => 'boolean',
        'ditutup' => 'boolean'
    ];

    /*
    |--------------------------------------------------------------------------
    | 🔥 FIX POSTGRESQL BOOLEAN
    |--------------------------------------------------------------------------
    */
    public static function periodeAktif()
    {
        return self::whereRaw('aktif = true')->first();
    }
}