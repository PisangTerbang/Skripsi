<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class Periode extends Model
{
    use HasFactory;

    protected $table = 'periode';

    protected $fillable = [
        'semester',
        'tahun_ajaran',
        'nama',
        'tahun_akademik',
        'tanggal_buka',
        'tanggal_tutup',
        'is_active',
        'aktif',
        'ditutup',
        'keterangan',
    ];

    protected $casts = [
        'tanggal_buka' => 'date',
        'tanggal_tutup' => 'date',
        'is_active' => 'boolean',
        'aktif' => 'boolean',
        'ditutup' => 'boolean',
    ];

    // ========== RELASI ==========

    public function pengajuan()
    {
        return $this->hasMany(Pengajuan::class);
    }

    // ========== SCOPE ==========

    /**
     * Scope untuk periode aktif
     */
    public function scopeActive($query)
    {
        return $query->whereRaw("is_active = true");
    }

    /**
     * Urutan kronologis periode: berdasarkan tanggal buka (fallback created_at
     * untuk data lama tanpa tanggal_buka), terbaru di atas. Mencegah urutan acak
     * akibat created_at yang tak selaras dengan tahun periode.
     */
    public function scopeUrutKronologis($query)
    {
        return $query->orderByRaw('COALESCE(tanggal_buka, created_at::date) DESC');
    }

    /**
     * Scope untuk periode yang sedang buka
     */
    public function scopeBuka($query)
    {
        $now = Carbon::now();
        return $query->whereRaw("is_active = true")
            ->whereDate('tanggal_buka', '<=', $now)
            ->whereDate('tanggal_tutup', '>=', $now);
    }

    // ========== STATIC METHODS ==========

    /** Cache periode aktif untuk satu request (dipanggil berkali-kali per halaman). */
    protected static $cachedPeriodeAktif = false;

    /**
     * Get periode yang sedang aktif.
     * Dimemoisasi per-request agar tidak query berulang (hemat latency ke DB remote).
     */
    public static function periodeAktif()
    {
        if (self::$cachedPeriodeAktif === false) {
            self::$cachedPeriodeAktif = self::whereRaw('is_active = true')->first();
        }

        return self::$cachedPeriodeAktif;
    }

    /** Reset cache periode aktif (panggil setelah mengubah periode yang aktif). */
    public static function lupakanPeriodeAktif(): void
    {
        self::$cachedPeriodeAktif = false;
    }

    // ========== METHODS ==========

    /**
     * Cek apakah periode sedang buka
     */
    public function isBuka()
    {
        if (!$this->is_active) {
            return false;
        }

        $now = Carbon::now();
        return $now->between($this->tanggal_buka, $this->tanggal_tutup);
    }

    /**
     * Cek apakah periode sudah tutup
     */
    public function isTutup()
    {
        if (!$this->is_active) {
            return true;
        }

        return Carbon::now()->gt($this->tanggal_tutup);
    }

    /**
     * Cek apakah periode belum dibuka
     */
    public function isBelumBuka()
    {
        if (!$this->is_active) {
            return false;
        }

        return Carbon::now()->lt($this->tanggal_buka);
    }

    // ========== ACCESSOR ==========

    /**
     * Status periode (Belum Dibuka / Sedang Buka / Sudah Ditutup / Nonaktif)
     */
    public function getStatusPeriodeAttribute()
    {
        if (!$this->is_active) {
            return 'Nonaktif';
        }

        $now = Carbon::now();

        if ($now->lt($this->tanggal_buka)) {
            return 'Belum Dibuka';
        }

        if ($now->between($this->tanggal_buka, $this->tanggal_tutup)) {
            return 'Sedang Buka';
        }

        return 'Sudah Ditutup';
    }

    /**
     * Badge color untuk status periode
     */
    public function getStatusBadgeColorAttribute()
    {
        return match ($this->status_periode) {
            'Sedang Buka' => 'green',
            'Belum Dibuka' => 'blue',
            'Sudah Ditutup' => 'gray',
            'Nonaktif' => 'red',
            default => 'gray',
        };
    }
}
