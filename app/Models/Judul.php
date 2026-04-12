<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\Laboratorium;
use App\Models\Pengajuan;

class Judul extends Model
{
    protected $table = 'judul';

    protected $fillable = [
        'laboratorium_id',
        'dosen_id',
        'nama_judul',
        'deskripsi',
        'aktif'
    ];

    protected $casts = [
        'aktif' => 'boolean',
    ];

    public function scopeAktif($query)
    {
        return $query->whereRaw('aktif = true');
    }

    public function laboratorium()
    {
        return $this->belongsTo(Laboratorium::class);
    }

    public function dosen()
    {
        return $this->belongsTo(User::class, 'dosen_id');
    }

    public function pengajuan()
    {
        return $this->hasMany(Pengajuan::class);
    }

    public function jumlahPeminat()
    {
        return $this->pengajuan()
            ->where('jenis', 'pilih')
            ->count();
    }

    public function jumlahDisetujui()
    {
        return $this->pengajuan()
            ->where('jenis', 'pilih')
            ->where('status', 'disetujui')
            ->count();
    }
}