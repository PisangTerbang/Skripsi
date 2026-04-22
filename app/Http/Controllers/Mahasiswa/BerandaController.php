<?php

namespace App\Http\Controllers\Mahasiswa;

use App\Http\Controllers\Controller;
use App\Models\Pengajuan;
use Illuminate\Support\Facades\Auth;

class BerandaController extends Controller
{
    public function index()
    {
        $mahasiswaId = Auth::id();

        // ================= USER STATS =================
        $total = Pengajuan::where('mahasiswa_id', $mahasiswaId)->count();

        $pending = Pengajuan::where('mahasiswa_id', $mahasiswaId)
            ->where('status', 'pending')
            ->count();

        $ditolak = Pengajuan::where('mahasiswa_id', $mahasiswaId)
            ->where('status', 'ditolak')
            ->count();

        // ================= GLOBAL STATS =================
        $totalSemua = Pengajuan::count();

        $disetujuiSemua = Pengajuan::where('status', 'disetujui')->count();

        // ================= JUDUL DISETUJUI (HERO) =================
        $disetujui = Pengajuan::with('judul')
            ->where('mahasiswa_id', $mahasiswaId)
            ->where('status', 'disetujui')
            ->latest()
            ->first();

        // ================= RIWAYAT TERAKHIR (FORMATTED) =================
        $riwayat = Pengajuan::with('judul')
            ->where('mahasiswa_id', $mahasiswaId)
            ->latest()
            ->take(5)
            ->get()
            ->map(function ($r) {
                return [
                    'judul' => $r->judul->nama_judul ?? $r->judul_mandiri,
                    'status' => $r->status,
                    'waktu' => $r->created_at->diffForHumans(),
                    'isNew' => false
                ];
            });

        return view('mahasiswa.beranda', compact(
            'total',
            'pending',
            'ditolak',
            'totalSemua',
            'disetujuiSemua',
            'disetujui',
            'riwayat'
        ))->with('title', 'Beranda');
    }

    public function data()
    {
        $mahasiswaId = Auth::id();

        $latest = Pengajuan::with('judul')
            ->where('mahasiswa_id', $mahasiswaId)
            ->latest()
            ->first();

        $status = $latest?->status ?? 'none';

        $jumlah = Pengajuan::where('mahasiswa_id', $mahasiswaId)->count();

        $riwayat = Pengajuan::with('judul')
            ->where('mahasiswa_id', $mahasiswaId)
            ->latest()
            ->take(5)
            ->get()
            ->map(function ($r) {
                return [
                    'judul' => $r->judul->nama_judul ?? $r->judul_mandiri,
                    'status' => $r->status,
                    'waktu' => $r->created_at->diffForHumans()
                ];
            });

        $notif = Pengajuan::where('mahasiswa_id', $mahasiswaId)
            ->whereIn('status', ['pending', 'review'])
            ->count();

        return response()->json([
            'status' => $status,
            'jumlah' => $jumlah,
            'riwayat' => $riwayat,
            'notif' => $notif
        ]);
    }
}
