<?php

namespace App\Http\Controllers\Prodi;

use App\Http\Controllers\Controller;
use App\Models\Pengajuan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PengajuanController extends Controller
{
    public function index()
    {
        // Keputusan final hanya untuk PERIODE AKTIF. Null-safe: tanpa periode aktif → kosong.
        $activePeriodeId = \App\Models\Periode::periodeAktif()?->id;

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
            ->where('periode_id', $activePeriodeId)
            ->where('status_kalab', 'disetujui')
            ->whereNull('status_kaprodi')
            ->orderByDesc('id')
            ->get();

        return view('prodi.pengajuan.index', compact('pengajuan'))
            ->with('title', 'Review Pengajuan Judul TA');
    }

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

        // Validation: Check if pengajuan can be reviewed by Kaprodi
        if ($pengajuan->isRejectedByKalab()) {
            return redirect()->route('prodi.pengajuan.index')
                ->with('error', 'Pengajuan ini telah ditolak oleh Ka Lab dan tidak dapat direview.');
        }

        if (!$pengajuan->canBeReviewedByKaprodi()) {
            return redirect()->route('prodi.pengajuan.index')
                ->with('error', 'Pengajuan ini tidak dapat direview saat ini.');
        }

        return view('prodi.pengajuan.show', compact('pengajuan'))
            ->with('title', 'Detail Pengajuan');
    }

    public function approve(Request $request, $id)
    {
        $validated = $request->validate([
            'catatan_kaprodi' => 'required|string|max:1000',
        ], [
            'catatan_kaprodi.required' => 'Catatan persetujuan wajib diisi.',
        ]);

        $pengajuan = Pengajuan::with('judulDitetapkan')->findOrFail($id);

        if (!$pengajuan->canBeReviewedByKaprodi()) {
            return back()->with('error', 'Pengajuan ini tidak dapat direview saat ini.');
        }

        if (!$pengajuan->isPeriodeAktif()) {
            return back()->with('error', 'Pengajuan ini berada di periode yang sudah ditutup (arsip) dan tidak dapat diproses.');
        }

        $success = $pengajuan->approveByKaprodi(
            Auth::id(),
            $validated['catatan_kaprodi'] ?? null
        );

        if ($success) {
            // ✅ Tidak ada notifikasi ke mahasiswa — menunggu pengumuman KoorTA

            return redirect()->route('prodi.pengajuan.index')
                ->with('success', 'Pengajuan berhasil disetujui. Mahasiswa akan mendapat notifikasi setelah pengumuman resmi dari Koordinator TA.');
        }

        return back()->with('error', 'Terjadi kesalahan saat menyetujui pengajuan.');
    }

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

        if (!$pengajuan->isPeriodeAktif()) {
            return back()->with('error', 'Pengajuan ini berada di periode yang sudah ditutup (arsip) dan tidak dapat diproses.');
        }

        $success = $pengajuan->rejectByKaprodi(
            Auth::id(),
            $validated['catatan_kaprodi']
        );

        if ($success) {
            // ✅ Tidak ada notifikasi ke mahasiswa — menunggu pengumuman KoorTA

            return redirect()->route('prodi.pengajuan.index')
                ->with('success', 'Pengajuan berhasil ditolak. Mahasiswa akan mendapat notifikasi setelah pengumuman resmi dari Koordinator TA.');
        }

        return back()->with('error', 'Terjadi kesalahan saat menolak pengajuan.');
    }

    public function riwayat(Request $request)
    {
        // Filter periode agar riwayat terstruktur. Default "semua" (seluruh riwayat),
        // bisa dipilih per periode.
        $periodeList = \App\Models\Periode::urutKronologis()->get();
        $aktifId = \App\Models\Periode::periodeAktif()?->id;
        // Default ke periode aktif (konsisten dgn Dosen/Ka Lab); filter tetap bisa lihat riwayat lama.
        $selectedPeriode = $request->get('periode_id') ?? ($aktifId ? (string) $aktifId : 'semua');

        $pengajuan = Pengajuan::with([
            'mahasiswa',
            'periode',
            'judulDitetapkan.dosen',
            'judulDitetapkan.laboratorium',
            'reviewerKaprodi',
        ])
            ->whereNotNull('status_kaprodi')
            ->when($selectedPeriode !== 'semua', fn($q) => $q->where('periode_id', $selectedPeriode))
            ->latest('tanggal_review_kaprodi')
            ->get();

        return view('prodi.pengajuan.riwayat', compact('pengajuan', 'periodeList', 'selectedPeriode', 'aktifId'))
            ->with('title', 'Riwayat Review Pengajuan');
    }
}
