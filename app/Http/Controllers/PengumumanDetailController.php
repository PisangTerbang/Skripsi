<?php

namespace App\Http\Controllers;

use App\Models\Pengajuan;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class PengumumanDetailController extends Controller
{
    /**
     * Halaman detail pengumuman (dapat diakses semua role yang login).
     * Menampilkan tabel hasil penetapan judul untuk periode terkait.
     */
    public function show($id)
    {
        $pengumuman = DB::table('pengumuman')
            ->join('periode', 'pengumuman.periode_id', '=', 'periode.id')
            ->leftJoin('users', 'pengumuman.dibuat_oleh', '=', 'users.id')
            ->select(
                'pengumuman.*',
                'periode.nama as nama_periode',
                'users.name as nama_pembuat'
            )
            ->where('pengumuman.id', $id)
            // Anti-bocor: pengumuman draft (belum dikirim) tidak boleh dibuka —
            // hasil hanya tampil setelah Koordinator TA broadcast (dikirim_at terisi).
            ->whereNotNull('pengumuman.dikirim_at')
            ->firstOrFail();

        // Hasil per-periode: pengajuan yang sudah diputuskan (diterima/ditolak)
        $hasil = Pengajuan::with(['mahasiswa', 'judulDitetapkan.dosen'])
            ->where('periode_id', $pengumuman->periode_id)
            ->whereIn('status', ['disetujui', 'ditolak'])
            ->get()
            ->sortBy(fn($p) => $p->mahasiswa->name ?? '')
            ->values();

        return view('pengumuman.detail', [
            'pengumuman' => $pengumuman,
            'hasil' => $hasil,
            'myId' => Auth::id(),
            'title' => 'Detail Pengumuman',
        ]);
    }
}
