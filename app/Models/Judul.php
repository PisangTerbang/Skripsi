<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Judul extends Model
{
    use HasFactory;

    protected $table = 'judul';

    protected $fillable = [
        'dosen_id',
        'laboratorium_id',
        'judul',
        'nama_judul',
        'deskripsi',
        'is_active',
        'is_locked',
        'status_judul',
        'kuota_maksimal',
        'jumlah_peminat',
        'status',
        'is_available',
        'catatan_kalab',           // BARU
        'reviewed_by_kalab',       // BARU
        'reviewed_at_kalab',       // BARU
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'is_locked' => 'boolean',
        'is_available' => 'boolean',
        'reviewed_at_kalab' => 'datetime',
    ];

    // ========== RELASI ==========

    public function dosen()
    {
        return $this->belongsTo(User::class, 'dosen_id');
    }

    public function laboratorium()
    {
        return $this->belongsTo(Laboratorium::class);
    }

    public function reviewerKalab()
    {
        return $this->belongsTo(User::class, 'reviewed_by_kalab');
    }

    // Relasi untuk backward compatibility (sistem lama)
    public function pengajuan()
    {
        return $this->hasMany(Pengajuan::class, 'judul_id');
    }

    // Relasi untuk workflow baru (3 pilihan)
    public function pengajuanPilihan1()
    {
        return $this->hasMany(Pengajuan::class, 'pilihan_1_id');
    }

    public function pengajuanPilihan2()
    {
        return $this->hasMany(Pengajuan::class, 'pilihan_2_id');
    }

    public function pengajuanPilihan3()
    {
        return $this->hasMany(Pengajuan::class, 'pilihan_3_id');
    }

    public function pengajuanDitetapkan()
    {
        return $this->hasMany(Pengajuan::class, 'judul_ditetapkan_id');
    }

    // ========== SCOPE ==========

    /**
     * Scope untuk judul aktif (menggunakan kolom baru)
     */
    public function scopeActive($query)
    {
        return $query->whereRaw("is_active = true")
            ->whereRaw("is_available = true")
            ->where('status', 'available');
    }

    /**
     * Scope untuk judul yang tersedia untuk mahasiswa
     */
    public function scopeAvailable($query)
    {
        return $query->where('status', 'available')
            ->whereRaw("is_available = true");
    }

    /**
     * Scope untuk judul berdasarkan laboratorium
     */
    public function scopeByLaboratorium($query, $labId)
    {
        return $query->where('laboratorium_id', $labId);
    }

    /**
     * Scope untuk judul pending validasi Kepala Lab
     */
    public function scopePendingKalab($query)
    {
        return $query->where('status', 'pending_kalab');
    }

    /**
     * Scope untuk judul yang sudah divalidasi Kalab
     */
    public function scopeValidatedByKalab($query)
    {
        return $query->whereIn('status', ['available', 'inactive'])
            ->whereNotNull('reviewed_by_kalab');
    }

    // ========== ACCESSOR ==========

    /**
     * Total peminat (yang memilih judul ini di pilihan 1, 2, atau 3)
     */
    public function getTotalPeminatAttribute()
    {
        return $this->pengajuanPilihan1()->count()
            + $this->pengajuanPilihan2()->count()
            + $this->pengajuanPilihan3()->count();
    }

    /**
     * Jumlah yang sudah ditetapkan (final)
     */
    public function getJumlahDitetapkanAttribute()
    {
        return $this->pengajuanDitetapkan()->count();
    }

    /**
     * Cek apakah kuota penuh
     */
    public function isKuotaPenuh()
    {
        if (!$this->kuota_maksimal) {
            return false; // unlimited
        }

        return $this->jumlah_ditetapkan >= $this->kuota_maksimal;
    }

    /**
     * Sisa kuota
     */
    public function getSisaKuotaAttribute()
    {
        if (!$this->kuota_maksimal) {
            return '∞'; // unlimited
        }

        $sisa = $this->kuota_maksimal - $this->jumlah_ditetapkan;
        return max(0, $sisa);
    }

    /**
     * Cek apakah judul bisa dipilih mahasiswa
     */
    public function isTersedia()
    {
        return $this->status === 'available'
            && $this->is_available
            && $this->is_active
            && !$this->isKuotaPenuh();
    }

    /**
     * Status label untuk display
     */
    public function getStatusLabelAttribute()
    {
        return match ($this->status) {
            'draft' => 'Draft',
            'pending_kalab' => 'Menunggu Validasi Kepala Lab',
            'available' => 'Tersedia',
            'inactive' => 'Tidak Aktif',
            default => 'Unknown'
        };
    }

    // ========== METHODS ==========

    /**
     * Approve judul oleh Kepala Lab
     */
    public function approveByKalab($userId, $catatan = null)
    {
        return $this->update([
            'status' => 'available',
            'is_available' => true,
            'catatan_kalab' => $catatan,
            'reviewed_by_kalab' => $userId,
            'reviewed_at_kalab' => now(),
        ]);
    }

    /**
     * Reject judul oleh Kepala Lab (kembalikan ke draft)
     */
    public function rejectByKalab($userId, $catatan)
    {
        return $this->update([
            'status' => 'draft',
            'is_available' => false,
            'catatan_kalab' => $catatan,
            'reviewed_by_kalab' => $userId,
            'reviewed_at_kalab' => now(),
        ]);
    }
}
