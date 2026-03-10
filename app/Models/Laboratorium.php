<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Laboratorium extends Model
{
    protected $table = 'laboratorium';

    protected $fillable = [
        'nama',
        'deskripsi'
    ];

    public function judul()
    {
        return $this->hasMany(Judul::class);
    }

    public function jumlahPeminat()
    {
        return \App\Models\Pengajuan::whereIn('judul_id', $this->judul()->pluck('id'))
            ->where('jenis', 'pilih')
            ->count();
    }

    public function jumlahDisetujui()
    {
        return \App\Models\Pengajuan::whereIn('judul_id', $this->judul()->pluck('id'))
            ->where('jenis', 'pilih')
            ->where('status', 'disetujui')
            ->count();
    }
}
