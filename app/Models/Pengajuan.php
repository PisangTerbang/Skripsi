<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class Pengajuan extends Model
{
    use HasFactory;

    protected $table = 'pengajuan';

    protected $fillable = [
        'mahasiswa_id',
        'periode_id',
        'pilihan_1_id',
        'pilihan_2_id',
        'pilihan_3_id',
        'alasan_1',
        'alasan_2',
        'alasan_3',
        'judul_mandiri',
        'deskripsi_mandiri',
        'status',
        // Ka Lab
        'status_kalab',
        'catatan_kalab_pengajuan',
        'tanggal_review_kalab',
        'reviewed_by_kalab',
        // Koor Lab (tidak dipakai di workflow, disimpan untuk histori)
        'status_koor',
        'catatan_koor_pengajuan',
        'tanggal_review_koor',
        'reviewed_by_koor',
        // Kaprodi
        'status_kaprodi',
        'catatan_kaprodi',
        'tanggal_review_kaprodi',
        'reviewed_by_kaprodi',
        // Judul Ditetapkan
        'judul_ditetapkan_id',
        'sumber_judul',
        'jenis',
        'dosen_pembimbing_id',
    ];

    protected $casts = [
        'tanggal_review_kalab' => 'datetime',
        'tanggal_review_koor' => 'datetime',
        'tanggal_review_kaprodi' => 'datetime',
    ];

    // ==================== RELASI ====================

    public function mahasiswa()
    {
        return $this->belongsTo(User::class, 'mahasiswa_id');
    }

    public function periode()
    {
        return $this->belongsTo(Periode::class);
    }

    public function pilihan1()
    {
        return $this->belongsTo(Judul::class, 'pilihan_1_id');
    }

    public function pilihan2()
    {
        return $this->belongsTo(Judul::class, 'pilihan_2_id');
    }

    public function pilihan3()
    {
        return $this->belongsTo(Judul::class, 'pilihan_3_id');
    }

    public function judulDitetapkan()
    {
        return $this->belongsTo(Judul::class, 'judul_ditetapkan_id');
    }

    public function judul()
    {
        return $this->belongsTo(Judul::class, 'judul_ditetapkan_id');
    }

    public function reviewerKalab()
    {
        return $this->belongsTo(User::class, 'reviewed_by_kalab');
    }

    public function reviewerKoor()
    {
        return $this->belongsTo(User::class, 'reviewed_by_koor');
    }

    public function reviewerKaprodi()
    {
        return $this->belongsTo(User::class, 'reviewed_by_kaprodi');
    }

    // ==================== QUERY SCOPES ====================

    public function scopePendingKalabReview($query)
    {
        return $query->where('status', 'pending')->whereNull('status_kalab');
    }

    public function scopePendingKoorReview($query)
    {
        return $query->where('status_kalab', 'disetujui')->whereNull('status_koor');
    }

    public function scopePendingKaprodiReview($query)
    {
        return $query->where('status_kalab', 'disetujui')->whereNull('status_kaprodi');
    }

    public function scopeApprovedByKalab($query)
    {
        return $query->where('status_kalab', 'disetujui');
    }

    public function scopeApprovedByKoor($query)
    {
        return $query->where('status_koor', 'disetujui');
    }

    public function scopeFinalApproved($query)
    {
        return $query->where('status_kaprodi', 'disetujui');
    }

    // ==================== STATUS CHECKERS ====================

    public function isPending()
    {
        return $this->status === 'pending';
    }

    public function isApproved()
    {
        return $this->status === 'disetujui';
    }

    public function isRejected()
    {
        return $this->status === 'ditolak';
    }

    public function isFinalApproved()
    {
        return $this->status_kaprodi === 'disetujui';
    }

    public function isRejectedByKalab()
    {
        return $this->status_kalab === 'ditolak';
    }

    public function isRejectedByKoor()
    {
        return $this->status_koor === 'ditolak';
    }

    public function isRejectedByKaprodi()
    {
        return $this->status_kaprodi === 'ditolak';
    }

    // ==================== REVIEW CHECKERS ====================

    public function needsKalabReview()
    {
        return $this->status === 'pending' && is_null($this->status_kalab);
    }

    public function needsKoorReview()
    {
        return $this->status_kalab === 'disetujui' && is_null($this->status_koor);
    }

    public function needsKaprodiReview()
    {
        return $this->status_kalab === 'disetujui' && is_null($this->status_kaprodi);
    }

    public function canBeReviewedByKalab()
    {
        return $this->status === 'pending' && is_null($this->status_kalab);
    }

    public function canBeReviewedByKoor()
    {
        return $this->status_kalab === 'disetujui' && is_null($this->status_koor);
    }

    public function canBeReviewedByKaprodi()
    {
        return $this->status_kalab === 'disetujui' && is_null($this->status_kaprodi);
    }

    // ==================== ACTION METHODS ====================

    public function approveByKalab($userId, $judulId, $sumberJudul, $catatan = null)
    {
        DB::beginTransaction();
        try {
            $this->update([
                'status_kalab' => 'disetujui',
                'catatan_kalab_pengajuan' => $catatan,
                'tanggal_review_kalab' => now(),
                'reviewed_by_kalab' => $userId,
                'judul_ditetapkan_id' => $judulId,
                'sumber_judul' => $sumberJudul,
            ]);

            $this->logActivity($userId, 'disetujui_kalab', 'Ka Lab menyetujui pengajuan', $catatan);

            DB::commit();
            return true;
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('approveByKalab error: ' . $e->getMessage(), [
                'pengajuan_id' => $this->id,
                'judul_id' => $judulId,
                'sumber_judul' => $sumberJudul,
                'trace' => $e->getTraceAsString(),
            ]);
            return false;
        }
    }

    public function rejectByKalab($userId, $catatan)
    {
        DB::beginTransaction();
        try {
            $this->update([
                'status_kalab' => 'ditolak',
                'catatan_kalab_pengajuan' => $catatan,
                'tanggal_review_kalab' => now(),
                'reviewed_by_kalab' => $userId,
                'status' => 'ditolak',
            ]);

            $this->logActivity($userId, 'ditolak_kalab', 'Ka Lab menolak pengajuan', $catatan);

            DB::commit();
            return true;
        } catch (\Exception $e) {
            DB::rollBack();
            return false;
        }
    }

    public function approveByKoor($userId, $catatan = null)
    {
        DB::beginTransaction();
        try {
            $this->update([
                'status_koor' => 'disetujui',
                'catatan_koor_pengajuan' => $catatan,
                'tanggal_review_koor' => now(),
                'reviewed_by_koor' => $userId,
            ]);

            $this->logActivity($userId, 'disetujui_koor', 'Koor Lab menyetujui pengajuan', $catatan);

            DB::commit();
            return true;
        } catch (\Exception $e) {
            DB::rollBack();
            return false;
        }
    }

    public function rejectByKoor($userId, $catatan)
    {
        DB::beginTransaction();
        try {
            $this->update([
                'status_koor' => 'ditolak',
                'catatan_koor_pengajuan' => $catatan,
                'tanggal_review_koor' => now(),
                'reviewed_by_koor' => $userId,
                'status' => 'ditolak',
            ]);

            $this->logActivity($userId, 'ditolak_koor', 'Koor Lab menolak pengajuan', $catatan);

            DB::commit();
            return true;
        } catch (\Exception $e) {
            DB::rollBack();
            return false;
        }
    }

    public function approveByKaprodi($userId, $catatan = null)
    {
        DB::beginTransaction();
        try {
            $this->update([
                'status_kaprodi' => 'disetujui',
                'catatan_kaprodi' => $catatan,
                'tanggal_review_kaprodi' => now(),
                'reviewed_by_kaprodi' => $userId,
                'status' => 'disetujui',
            ]);

            if ($this->judul_ditetapkan_id) {
                DB::table('judul')->where('id', $this->judul_ditetapkan_id)->update([
                    'is_locked' => DB::raw('true'),
                    'updated_at' => now(),
                ]);
            }

            $this->logActivity($userId, 'disetujui_kaprodi', 'Kaprodi menyetujui pengajuan (Final Approval)', $catatan);

            DB::commit();
            return true;
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('approveByKaprodi error: ' . $e->getMessage(), [
                'pengajuan_id' => $this->id,
                'trace' => $e->getTraceAsString(),
            ]);
            return false;
        }
    }

    public function rejectByKaprodi($userId, $catatan)
    {
        DB::beginTransaction();
        try {
            $this->update([
                'status_kaprodi' => 'ditolak',
                'catatan_kaprodi' => $catatan,
                'tanggal_review_kaprodi' => now(),
                'reviewed_by_kaprodi' => $userId,
                'status' => 'ditolak',
            ]);

            $this->logActivity($userId, 'ditolak_kaprodi', 'Kaprodi menolak pengajuan', $catatan);

            DB::commit();
            return true;
        } catch (\Exception $e) {
            DB::rollBack();
            return false;
        }
    }

    // ==================== HELPER METHODS ====================

    private function logActivity($userId, $tipe, $pesan, $catatan = null)
    {
        $fullPesan = $pesan;
        if ($catatan) {
            $fullPesan .= ' - Catatan: ' . $catatan;
        }

        DB::table('aktivitas')->insert([
            'user_id' => $this->mahasiswa_id,
            'tipe' => $tipe,
            'pesan' => $fullPesan,
            'is_read' => DB::raw('false'),
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    // ✅ Helper: cek apakah sudah diumumkan KoorTA
    private function sudahDiumumkan(): bool
    {
        return DB::table('aktivitas')
            ->where('user_id', $this->mahasiswa_id)
            ->whereIn('tipe', ['pengumuman_disetujui', 'pengumuman_ditolak'])
            ->exists();
    }

    public function getJudulOptions()
    {
        $options = [];

        if ($this->pilihan_1_id) {
            $options[] = [
                'id' => $this->pilihan_1_id,
                'judul' => $this->pilihan1,
                'alasan' => $this->alasan_1,
                'nomor' => 1,
            ];
        }

        if ($this->pilihan_2_id) {
            $options[] = [
                'id' => $this->pilihan_2_id,
                'judul' => $this->pilihan2,
                'alasan' => $this->alasan_2,
                'nomor' => 2,
            ];
        }

        if ($this->pilihan_3_id) {
            $options[] = [
                'id' => $this->pilihan_3_id,
                'judul' => $this->pilihan3,
                'alasan' => $this->alasan_3,
                'nomor' => 3,
            ];
        }

        return $options;
    }

    // ==================== STATUS BADGES ====================

    public function getStatusBadgeAttribute()
    {
        return match ($this->status) {
            'pending' => '<span class="px-3 py-1 text-xs font-semibold rounded-full bg-yellow-100 text-yellow-800">Menunggu Review</span>',
            'disetujui' => '<span class="px-3 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">Disetujui</span>',
            'ditolak' => '<span class="px-3 py-1 text-xs font-semibold rounded-full bg-red-100 text-red-800">Ditolak</span>',
            default => '<span class="px-3 py-1 text-xs font-semibold rounded-full bg-gray-100 text-gray-800">Unknown</span>',
        };
    }

    public function getKalabStatusBadgeAttribute()
    {
        if (is_null($this->status_kalab)) {
            return '<span class="px-3 py-1 text-xs font-semibold rounded-full bg-gray-100 text-gray-600">Belum Review</span>';
        }

        return match ($this->status_kalab) {
            'disetujui' => '<span class="px-3 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">✓ Ka Lab</span>',
            'ditolak' => '<span class="px-3 py-1 text-xs font-semibold rounded-full bg-red-100 text-red-800">✗ Ka Lab</span>',
            default => '<span class="px-3 py-1 text-xs font-semibold rounded-full bg-gray-100 text-gray-800">Unknown</span>',
        };
    }

    public function getKoorStatusBadgeAttribute()
    {
        if (is_null($this->status_koor)) {
            return '<span class="px-3 py-1 text-xs font-semibold rounded-full bg-gray-100 text-gray-600">Belum Review</span>';
        }

        return match ($this->status_koor) {
            'disetujui' => '<span class="px-3 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">✓ Koor Lab</span>',
            'ditolak' => '<span class="px-3 py-1 text-xs font-semibold rounded-full bg-red-100 text-red-800">✗ Koor Lab</span>',
            default => '<span class="px-3 py-1 text-xs font-semibold rounded-full bg-gray-100 text-gray-800">Unknown</span>',
        };
    }

    public function getKaprodiStatusBadgeAttribute()
    {
        if (is_null($this->status_kaprodi)) {
            return '<span class="px-3 py-1 text-xs font-semibold rounded-full bg-gray-100 text-gray-600">Belum Review</span>';
        }

        return match ($this->status_kaprodi) {
            'disetujui' => '<span class="px-3 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">✓ Kaprodi</span>',
            'ditolak' => '<span class="px-3 py-1 text-xs font-semibold rounded-full bg-red-100 text-red-800">✗ Kaprodi</span>',
            default => '<span class="px-3 py-1 text-xs font-semibold rounded-full bg-gray-100 text-gray-800">Unknown</span>',
        };
    }

    // ==================== PROGRESS TRACKER ====================

    public function getProgressPercentageAttribute()
    {
        $steps = 4; // ✅ Submit, Ka Lab, Kaprodi, Pengumuman
        $completed = 0;

        // Step 1: Submitted
        if ($this->status !== null)
            $completed++;

        // Step 2: Ka Lab approve
        if ($this->status_kalab === 'disetujui')
            $completed++;

        // Step 3: Kaprodi approve
        if ($this->status_kaprodi === 'disetujui')
            $completed++;

        // Step 4: Pengumuman KoorTA
        if ($this->sudahDiumumkan())
            $completed++;

        return ($completed / $steps) * 100;
    }

    public function getCurrentStepAttribute()
    {
        if ($this->status === 'ditolak')
            return 'Ditolak';

        if (is_null($this->status_kalab))
            return 'Menunggu Review Ka Lab';
        if ($this->status_kalab === 'ditolak')
            return 'Ditolak oleh Ka Lab';

        if (is_null($this->status_kaprodi))
            return 'Menunggu Review Kaprodi';
        if ($this->status_kaprodi === 'ditolak')
            return 'Ditolak oleh Kaprodi — Menunggu Pengumuman';

        // Kaprodi sudah approve — cek pengumuman
        if ($this->sudahDiumumkan())
            return 'Selesai — Pengumuman Sudah Dikirim';

        return 'Menunggu Pengumuman Koordinator TA';
    }
}
