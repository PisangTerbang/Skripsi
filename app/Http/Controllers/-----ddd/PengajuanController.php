<?php

namespace App\Http\Controllers\KepalaLab;

use App\Http\Controllers\Controller;
use App\Models\Pengajuan;
use App\Models\Judul;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class PengajuanController extends Controller
{
    /**
     * Tampilkan daftar pengajuan mahasiswa yang perlu direview Ka Lab
     */
    public function index()
    {
        $pengajuanPending = Pengajuan::with([
            'mahasiswa',
            'periode',
            'pilihan1.laboratorium',
            'pilihan2.laboratorium',
            'pilihan3.laboratorium'
        ])
            ->pendingKalabReview()
            ->orderBy('created_at', 'asc')
            ->get();

        $pengajuanSelesai = Pengajuan::with([
            'mahasiswa',
            'periode',
            'judulDitetapkan',
            'reviewerKalab'
        ])
            ->whereNotNull('status_kalab')
            ->orderBy('tanggal_review_kalab', 'desc')
            ->take(20)
            ->get();

        return view('kepala-lab.pengajuan.index', [
            'title' => 'Review Pengajuan Mahasiswa',
            'pengajuanPending' => $pengajuanPending,
            'pengajuanSelesai' => $pengajuanSelesai,
        ]);
    }

    /**
     * Tampilkan detail pengajuan untuk review
     */
    public function show($id)
    {
        $pengajuan = Pengajuan::with([
            'mahasiswa',
            'periode',
            'pilihan1.laboratorium',
            'pilihan1.dosen',
            'pilihan2.laboratorium',
            'pilihan2.dosen',
            'pilihan3.laboratorium',
            'pilihan3.dosen',
        ])->findOrFail($id);

        // Cek apakah Ka Lab bisa review pengajuan ini
        if (!$pengajuan->canBeReviewedByKalab()) {
            return redirect()
                ->route('kepala-lab.pengajuan.index')
                ->with('error', 'Pengajuan ini tidak dapat direview.');
        }

        return view('kepala-lab.pengajuan.show', [
            'title' => 'Detail Pengajuan',
            'pengajuan' => $pengajuan,
        ]);
    }

    /**
     * Approve pengajuan dan tetapkan judul
     */
    public function approve(Request $request, $id)
    {
        $request->validate([
            'judul_terpilih' => 'required|in:pilihan_1,pilihan_2,pilihan_3,mandiri',
            'catatan_kalab' => 'nullable|string|max:1000',
        ]);

        $pengajuan = Pengajuan::findOrFail($id);

        // Validasi apakah bisa direview
        if (!$pengajuan->canBeReviewedByKalab()) {
            return back()->with('error', 'Pengajuan ini tidak dapat direview.');
        }

        DB::beginTransaction();
        try {
            $user = Auth::user();
            $sumberJudul = $request->judul_terpilih;
            $judulId = null;

            // Tentukan judul yang dipilih
            if ($sumberJudul === 'pilihan_1') {
                $judulId = $pengajuan->pilihan_1_id;
            } elseif ($sumberJudul === 'pilihan_2') {
                $judulId = $pengajuan->pilihan_2_id;
            } elseif ($sumberJudul === 'pilihan_3') {
                $judulId = $pengajuan->pilihan_3_id;
            } elseif ($sumberJudul === 'mandiri') {
                // Buat judul baru dari judul mandiri
                $judulId = $this->createJudulMandiri($pengajuan);
            }

            // Approve menggunakan method di model
            $success = $pengajuan->approveByKalab(
                userId: $user->id,
                judulId: $judulId,
                sumberJudul: $sumberJudul,
                catatan: $request->catatan_kalab
            );

            if (!$success) {
                throw new \Exception('Gagal menyetujui pengajuan.');
            }

            // Notifikasi ke mahasiswa
            DB::table('aktivitas')->insert([
                'user_id' => $pengajuan->mahasiswa_id,
                'tipe' => 'pengajuan_disetujui_kalab',
                'pesan' => 'Pengajuan Anda telah disetujui oleh Kepala Lab. Menunggu review Koordinator Lab.',
                'is_read' => false,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            DB::commit();

            return redirect()
                ->route('kepala-lab.pengajuan.index')
                ->with('success', 'Pengajuan berhasil disetujui dan diteruskan ke Koordinator Lab.');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    /**
     * Reject pengajuan
     */
    public function reject(Request $request, $id)
    {
        $request->validate([
            'catatan_kalab' => 'required|string|max:1000',
        ]);

        $pengajuan = Pengajuan::findOrFail($id);

        // Validasi apakah bisa direview
        if (!$pengajuan->canBeReviewedByKalab()) {
            return back()->with('error', 'Pengajuan ini tidak dapat direview.');
        }

        DB::beginTransaction();
        try {
            $user = Auth::user();

            // Reject menggunakan method di model
            $success = $pengajuan->rejectByKalab(
                userId: $user->id,
                catatan: $request->catatan_kalab
            );

            if (!$success) {
                throw new \Exception('Gagal menolak pengajuan.');
            }

            // Notifikasi ke mahasiswa
            DB::table('aktivitas')->insert([
                'user_id' => $pengajuan->mahasiswa_id,
                'tipe' => 'pengajuan_ditolak_kalab',
                'pesan' => 'Pengajuan Anda ditolak oleh Kepala Lab. Catatan: ' . $request->catatan_kalab,
                'is_read' => false,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            DB::commit();

            return redirect()
                ->route('kepala-lab.pengajuan.index')
                ->with('success', 'Pengajuan berhasil ditolak.');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    /**
     * Helper: Buat judul baru dari judul mandiri mahasiswa
     */
    private function createJudulMandiri($pengajuan)
    {
        $judul = Judul::create([
            'nama_judul' => $pengajuan->judul_mandiri,
            'deskripsi' => $pengajuan->deskripsi_mandiri,
            'dosen_id' => null, // Belum ada dosen pembimbing
            'laboratorium_id' => null, // Akan ditentukan kemudian
            'status_judul' => 'ditawarkan',
            'aktif' => true,
            'is_locked' => false,
            'sumber' => 'mahasiswa',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Log pembuatan judul mandiri
        DB::table('judul_logs')->insert([
            'judul_id' => $judul->id,
            'user_id' => Auth::id(),
            'aksi' => 'dibuat_dari_pengajuan',
            'dari_status' => null,
            'ke_status' => 'ditawarkan',
            'catatan' => 'Judul mandiri dari pengajuan mahasiswa: ' . $pengajuan->mahasiswa->name,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return $judul->id;
    }
}
