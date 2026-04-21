<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\Judul;
use App\Models\Periode;
use App\Models\Aktivitas;

class Pengajuan extends Model
{
    protected $table = 'pengajuan';

    protected $fillable = [
        'mahasiswa_id',
        'judul_id',
        'judul_mandiri',
        'deskripsi_mandiri',
        'dosen_pilihan_id',
        'jenis',
        'prioritas',
        'alasan',
        'status',
        'catatan_dosen',
        'periode_id'
    ];

    /*
    |--------------------------------------------------------------------------
    | RELATIONSHIP
    |--------------------------------------------------------------------------
    */

    public function mahasiswa()
    {
        return $this->belongsTo(User::class, 'mahasiswa_id');
    }

    public function judul()
    {
        return $this->belongsTo(Judul::class);
    }

    public function dosenPilihan()
    {
        return $this->belongsTo(User::class, 'dosen_pilihan_id');
    }

    public function periode()
    {
        return $this->belongsTo(Periode::class);
    }

    /*
    |--------------------------------------------------------------------------
    | MODEL EVENTS
    |--------------------------------------------------------------------------
    */

    protected static function booted()
    {
        static::updating(function ($pengajuan) {

            $statusLama = $pengajuan->getOriginal('status');
            $statusBaru = $pengajuan->status;

            // 🔒 LOCK FIELD KRITIS
            if (
                in_array($statusLama, ['disetujui', 'ditolak']) &&
                (
                    $pengajuan->isDirty('status') ||
                    $pengajuan->isDirty('judul_id') ||
                    $pengajuan->isDirty('prioritas') ||
                    $pengajuan->isDirty('jenis')
                )
            ) {
                throw new \Exception('Pengajuan yang sudah diputuskan tidak dapat diubah.');
            }

            // ✅ NOTIF DISETUJUI
            if ($statusLama === 'pending' && $statusBaru === 'disetujui') {
                Aktivitas::buat(
                    $pengajuan->mahasiswa_id,
                    'persetujuan',
                    'Pengajuan judul Anda telah disetujui.'
                );
            }

            // ❌ NOTIF DITOLAK
            if ($statusLama === 'pending' && $statusBaru === 'ditolak') {
                Aktivitas::buat(
                    $pengajuan->mahasiswa_id,
                    'penolakan',
                    'Pengajuan ditolak. Catatan: ' . ($pengajuan->catatan_dosen ?? '-')
                );
            }
        });

        static::creating(function ($pengajuan) {

            if (!$pengajuan->periode_id) {

                // 🔥 POSTGRES SAFE BOOLEAN
                $periode = Periode::whereRaw('aktif IS TRUE')->first();

                if (!$periode) {
                    throw new \Exception('Tidak ada periode aktif.');
                }

                if ($periode->ditutup) {
                    throw new \Exception('Periode sudah ditutup.');
                }

                $pengajuan->periode_id = $periode->id;
            }
        });
    }
}