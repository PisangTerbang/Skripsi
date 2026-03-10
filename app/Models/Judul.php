<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

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
