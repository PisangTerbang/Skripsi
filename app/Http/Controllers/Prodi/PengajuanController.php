<?php

namespace App\Http\Controllers\Prodi;

use App\Http\Controllers\Controller;
use App\Models\Pengajuan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class PengajuanController extends Controller
{
    /**
     * Tampilkan daftar pengajuan yang perlu direview Kaprodi
     */
    public function index()
    {
        $pengajuan = Pengajuan::with([
            'mahasiswa',
            'periode',
            'pilihan1.dosen',
            'pilihan1.laboratorium',
            'pilihan2.dosen',
            'pilihan2.laboratorium',
            'pilihan3.dosen',
            'pilihan3.laboratorium',
            'judulDitetapkan.dosen',
            'judulDitetapkan.laboratorium',
            'reviewerKalab',
        ])
            ->where('status_kalab', 'disetujui')  // ✅ fix
            ->whereNull('status_kaprodi')
            ->latest()
            ->get();

        return view('prodi.pengajuan.index', compact('pengajuan'))
            ->with('title', 'Review Pengajuan Judul TA');
    }

    /**
     * Tampilkan detail pengajuan untuk review
     */
    public function show($id)
    {
        $pengajuan = Pengajuan::with([
            'mahasiswa',
            'periode',
            'pilihan1.dosen',
            'pilihan1.laboratorium',
            'pilihan2.dosen',
            'pilihan2.laboratorium',
            'pilihan3.dosen',
            'pilihan3.laboratorium',
            'judulDitetapkan.dosen',
            'judulDitetapkan.laboratorium',
            'reviewerKalab',
        ])->findOrFail($id);

        // canBeReviewedByKaprodi() sudah difix di model
        if (!$pengajuan->canBeReviewedByKaprodi()) {
            return redirect()->route('prodi.pengajuan.index')
                ->with('error', 'Pengajuan ini tidak dapat direview saat ini.');
        }

        return view('prodi.pengajuan.show', compact('pengajuan'))
            ->with('title', 'Detail Pengajuan');
    }

    /**
     * Setujui pengajuan oleh Kaprodi
     */
    public function approve(Request $request, $id)
    {
        $validated = $request->validate([
            'catatan_kaprodi' => 'nullable|string|max:1000',
        ]);

        $pengajuan = Pengajuan::with('judulDitetapkan')->findOrFail($id);

        if (!$pengajuan->canBeReviewedByKaprodi()) {
            return back()->with('error', 'Pengajuan ini tidak dapat direview saat ini.');
        }

        $success = $pengajuan->approveByKaprodi(
            Auth::id(),
            $validated['catatan_kaprodi'] ?? null
        );

        if ($success) {
            // ✅ fix: pakai ->judul (kolom yang ada di model Judul)
            $judulText = $pengajuan->judulDitetapkan->judul
                ?? $pengajuan->judulDitetapkan->nama_judul
                ?? 'judul TA Anda';

            DB::table('aktivitas')->insert([
                'user_id' => $pengajuan->mahasiswa_id,
                'tipe' => 'pengajuan_disetujui_kaprodi',
                'pesan' => 'Selamat! Pengajuan judul TA Anda telah disetujui oleh Kaprodi. Judul yang ditetapkan: ' . $judulText,
                'is_read' => DB::raw('false'),
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            return redirect()->route('prodi.pengajuan.index')
                ->with('success', 'Pengajuan berhasil disetujui. Judul telah dikunci dan mahasiswa akan menerima notifikasi.');
        }

        return back()->with('error', 'Terjadi kesalahan saat menyetujui pengajuan.');
    }

    /**
     * Tolak pengajuan oleh Kaprodi
     */
    public function reject(Request $request, $id)
    {
        $validated = $request->validate([
            'catatan_kaprodi' => 'required|string|max:1000',
        ], [
            'catatan_kaprodi.required' => 'Catatan penolakan wajib diisi',
        ]);

        $pengajuan = Pengajuan::findOrFail($id);

        if (!$pengajuan->canBeReviewedByKaprodi()) {
            return back()->with('error', 'Pengajuan ini tidak dapat direview saat ini.');
        }

        $success = $pengajuan->rejectByKaprodi(
            Auth::id(),
            $validated['catatan_kaprodi']
        );

        if ($success) {
            DB::table('aktivitas')->insert([
                'user_id' => $pengajuan->mahasiswa_id,
                'tipe' => 'pengajuan_ditolak_kaprodi',
                'pesan' => 'Pengajuan judul TA Anda ditolak oleh Kaprodi. Catatan: ' . $validated['catatan_kaprodi'],
                'is_read' => DB::raw('false'),
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            return redirect()->route('prodi.pengajuan.index')
                ->with('success', 'Pengajuan berhasil ditolak. Mahasiswa akan menerima notifikasi.');
        }

        return back()->with('error', 'Terjadi kesalahan saat menolak pengajuan.');
    }

    /**
     * Tampilkan riwayat semua pengajuan yang sudah direview
     */
    public function riwayat()
    {
        $pengajuan = Pengajuan::with([
            'mahasiswa',
            'periode',
            'judulDitetapkan.dosen',
            'judulDitetapkan.laboratorium',
            'reviewerKaprodi',
        ])
            ->whereNotNull('status_kaprodi')
            ->latest('tanggal_review_kaprodi')
            ->get(); // ✅ fix: dari paginate(20) → get() supaya collection methods jalan di view

        return view('prodi.pengajuan.riwayat', compact('pengajuan'))
            ->with('title', 'Riwayat Review Pengajuan');
    }
}
