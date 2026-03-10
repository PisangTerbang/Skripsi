<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Periode extends Model
{
    protected $table = 'periode';

    protected $fillable = [
        'semester',
        'tahun_ajaran'
    ];

    public function pengajuan()
    {
        return $this->hasMany(Pengajuan::class);
    }

    public static function periodeAktif()
    {
        return self::whereRaw('aktif = true')->first();
    }

    protected static function booted()
    {
        static::saving(function ($periode) {

            if ($periode->aktif) {

                self::where('id', '!=', $periode->id)
                    ->update(['aktif' => false]);
            }
        });
    }

    public function sudahDitutup()
    {
        return $this->ditutup;
    }
    protected $casts = [
        'aktif' => 'boolean',
        'ditutup' => 'boolean',
    ];
}
