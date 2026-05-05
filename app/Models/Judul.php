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
        'kode',
        'laboratorium_id',
        'dosen_id',
        'nama_judul',
        'deskripsi',
        'aktif',
        'is_locked'
    ];

    protected $casts = [
        'aktif' => 'boolean',
        'is_locked' => 'boolean',
    ];

    // ❌ JANGAN ADA DEFAULT ATTRIBUTES YANG SET BOOLEAN
    // protected $attributes = [
    //     'is_locked' => false
    // ];

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

    public function koorLab()
{
    return $this->belongsTo(User::class, 'koor_lab_id');
}

}