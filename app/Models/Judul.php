<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Judul extends Model
{
    use HasFactory;

    protected $table = 'judul';

    protected $fillable = [
        'dosen_id',
        'laboratorium_id',
        'kode',
        'nama_judul',
        'deskripsi',
        'aktif',
        'is_locked',
        'status_judul',
        'status',
        'is_available',
        'catatan_kalab',           // BARU
        'reviewed_by_kalab',       // BARU
        'reviewed_at_kalab',       // BARU
    ];

    protected $casts = [
        'aktif' => 'boolean',
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
        return $query->whereRaw("aktif = true")
            ->whereRaw("is_available = true")
            ->where('status', 'available');
    }

    /**
     * Scope untuk judul berdasarkan laboratorium
     */
    public function scopeByLaboratorium($query, $labId)
    {
        return $query->where('laboratorium_id', $labId);
    }

    // ========== ACCESSOR ==========

    /**
     * Total peminat (yang memilih judul ini di pilihan 1, 2, atau 3)
     */
    public function getTotalPeminatAttribute()
    {
        // Peminat dihitung untuk PERIODE AKTIF saja → ikut reset tiap ganti periode.
        $pid = Periode::periodeAktif()?->id;
        if (!$pid) {
            return 0;
        }

        return $this->pengajuanPilihan1()->where('periode_id', $pid)->count()
            + $this->pengajuanPilihan2()->where('periode_id', $pid)->count()
            + $this->pengajuanPilihan3()->where('periode_id', $pid)->count();
    }

    /**
     * Jumlah yang sudah ditetapkan (final) pada periode aktif
     */
    public function getJumlahDitetapkanAttribute()
    {
        $pid = Periode::periodeAktif()?->id;
        if (!$pid) {
            return 0;
        }

        return $this->pengajuanDitetapkan()->where('periode_id', $pid)->count();
    }

    // ========== METHODS ==========

    /**
     * Generate kode judul untuk sebuah lab, mengikuti pola kode yang sudah ada
     * (mis. SIRKEL-71). Prefix diambil dari kode judul lab tsb; fallback ke nama lab.
     */
    public static function generateKode($laboratoriumId): string
    {
        $existing = self::where('laboratorium_id', $laboratoriumId)
            ->whereNotNull('kode')
            ->pluck('kode');

        $prefix = $existing->isNotEmpty() && str_contains($existing->first(), '-')
            ? explode('-', $existing->first())[0]
            : strtoupper(str_replace(' ', '', optional(Laboratorium::find($laboratoriumId))->nama ?? 'JD'));

        $maxNum = $existing
            ->map(fn($k) => preg_match('/(\d+)$/', $k, $m) ? (int) $m[1] : 0)
            ->max() ?? 0;

        return $prefix . '-' . ($maxNum + 1);
    }
}
