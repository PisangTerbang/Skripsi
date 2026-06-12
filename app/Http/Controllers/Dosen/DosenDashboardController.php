<?php

namespace App\Http\Controllers\Dosen;

use App\Http\Controllers\Controller;
use App\Models\Pengajuan;
use App\Models\Periode;
use Illuminate\Support\Facades\DB;

class DosenDashboardController extends Controller
{
    public function index()
    {
        $periodeAktif = Periode::periodeAktif();
        // Null-safe: tanpa periode aktif semua hitungan jadi 0 (bukan error 500).
        $pid = $periodeAktif?->id;

        // ================= STATS =================
        $total = Pengajuan::where('periode_id', $pid)->count();
        $disetujui = Pengajuan::where('periode_id', $pid)
            ->where('status', 'disetujui')
            ->count();
        $ditolak = Pengajuan::where('periode_id', $pid)
            ->where('status', 'ditolak')
            ->count();
        $pending = Pengajuan::where('periode_id', $pid)
            ->where('status', 'pending')
            ->count();

        $totalSemua = max($total, 1); // cegah bagi 0

        $persenPending = round(($pending / $totalSemua) * 100);
        $persenSetuju = round(($disetujui / $totalSemua) * 100);
        $persenTolak = round(($ditolak / $totalSemua) * 100);

        // ================= TREN PENGAJUAN =================
        // Group per periode pakai 'nama' (kolom yang terisi); semester/tahun_ajaran
        // legacy & NULL sehingga dulu semua periode kolaps jadi satu label kosong.
        $trenPengajuan = Periode::select(
            'periode.nama',
            DB::raw('count(pengajuan.id) as total')
        )
            ->leftJoin('pengajuan', 'periode.id', '=', 'pengajuan.periode_id')
            ->groupBy('periode.id', 'periode.nama')
            ->orderBy('periode.id')
            ->get();

        // ================= TREN KEPUTUSAN =================
        $trenKeputusan = Periode::select(
            'periode.nama',
            DB::raw("count(case when pengajuan.status = 'disetujui' then 1 end) as disetujui"),
            DB::raw("count(case when pengajuan.status = 'ditolak' then 1 end) as ditolak")
        )
            ->leftJoin('pengajuan', 'periode.id', '=', 'pengajuan.periode_id')
            ->groupBy('periode.id', 'periode.nama')
            ->orderBy('periode.id')
            ->get();

        // ================= LAB STATS =================
        $labDisetujui = \App\Models\Laboratorium::select(
            'laboratorium.nama',
            DB::raw('count(pengajuan.id) as total')
        )
            ->join('judul', 'laboratorium.id', '=', 'judul.laboratorium_id')
            ->join('pengajuan', 'judul.id', '=', 'pengajuan.judul_ditetapkan_id')
            ->where('pengajuan.status', 'disetujui')
            ->where('pengajuan.periode_id', $pid)
            ->groupBy('laboratorium.nama')
            ->orderByDesc('total')
            ->take(5)
            ->get();

        $labDitolak = \App\Models\Laboratorium::select(
            'laboratorium.nama',
            DB::raw('count(pengajuan.id) as total')
        )
            ->join('judul', 'laboratorium.id', '=', 'judul.laboratorium_id')
            ->join('pengajuan', 'judul.id', '=', 'pengajuan.judul_ditetapkan_id')
            ->where('pengajuan.status', 'ditolak')
            ->where('pengajuan.periode_id', $pid)
            ->groupBy('laboratorium.nama')
            ->orderByDesc('total')
            ->take(5)
            ->get();

        $rasioLab = \App\Models\Laboratorium::select(
            'laboratorium.nama',
            DB::raw("count(case when pengajuan.status = 'disetujui' then 1 end) as disetujui"),
            DB::raw("count(case when pengajuan.status = 'ditolak' then 1 end) as ditolak")
        )
            ->join('judul', 'laboratorium.id', '=', 'judul.laboratorium_id')
            ->join('pengajuan', 'judul.id', '=', 'pengajuan.judul_ditetapkan_id')
            ->where('pengajuan.periode_id', $pid)
            ->groupBy('laboratorium.nama')
            ->get();

        // ================= RECENT SUBMISSIONS (NEW) =================
        $recentSubmissions = Pengajuan::with(['mahasiswa', 'judul'])
            ->where('periode_id', $pid)
            ->where('status', 'pending')
            ->latest()
            ->take(5)
            ->get();

        // ================= APPROVAL RATE =================
        $approvalRate = $total > 0 ? round(($disetujui / $total) * 100) : 0;

        return view('dosen.dashboard', [
            'total' => $total,
            'disetujui' => $disetujui,
            'ditolak' => $ditolak,
            'pending' => $pending,
            'persenPending' => $persenPending,
            'persenSetuju' => $persenSetuju,
            'persenTolak' => $persenTolak,
            'periodeAktif' => $periodeAktif,
            'trenPengajuan' => $trenPengajuan,
            'trenKeputusan' => $trenKeputusan,
            'labDisetujui' => $labDisetujui,
            'labDitolak' => $labDitolak,
            'rasioLab' => $rasioLab,
            'recentSubmissions' => $recentSubmissions,
            'approvalRate' => $approvalRate,
            'title' => 'Dashboard Dosen'
        ]);
    }
}
