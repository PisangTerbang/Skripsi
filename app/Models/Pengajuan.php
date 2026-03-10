<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
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
        'catatan_dosen'
    ];

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

    protected static function booted()
    {
        // Cegah perubahan jika sudah diputuskan
        static::updating(function ($pengajuan) {

            $statusLama = $pengajuan->getOriginal('status');
            $statusBaru = $pengajuan->status;

            // Jika sebelumnya sudah diputuskan → tidak boleh diubah lagi
            if (in_array($statusLama, ['disetujui', 'ditolak'])) {
                throw new \Exception('Pengajuan yang sudah diputuskan tidak dapat diubah.');
            }

            // Jika status berubah dari pending → disetujui
            if ($statusLama === 'pending' && $statusBaru === 'disetujui') {
                Aktivitas::create([
                    'user_id' => $pengajuan->mahasiswa_id,
                    'tipe' => 'persetujuan',
                    'pesan' => 'Pengajuan judul Anda telah disetujui.'
                ]);
            }

            // Jika status berubah dari pending → ditolak
            if ($statusLama === 'pending' && $statusBaru === 'ditolak') {
                Aktivitas::create([
                    'user_id' => $pengajuan->mahasiswa_id,
                    'tipe' => 'penolakan',
                    'pesan' => 'Pengajuan judul Anda ditolak. Catatan: ' . $pengajuan->catatan_dosen
                ]);
            }
        });

        // Otomatis isi periode aktif saat membuat pengajuan
        static::creating(function ($pengajuan) {

            if (!$pengajuan->periode_id) {
                $periode = \App\Models\Periode::periodeAktif();
                if ($periode) {

                    // Tambahan: cek apakah periode sudah ditutup
                    if ($periode->ditutup) {
                        throw new \Exception('Periode sudah ditutup.');
                    }

                    $pengajuan->periode_id = $periode->id;
                }
            }
        });
    }

    public function periode()
    {
        return $this->belongsTo(Periode::class);
    }
}
