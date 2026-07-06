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
        // Satu query agregat (FILTER) menggantikan 4 count terpisah → hemat latency DB remote.
        $s = Pengajuan::where('periode_id', $pid)->selectRaw("
            count(*)                                        as total,
            count(*) filter (where status = 'disetujui')    as disetujui,
            count(*) filter (where status = 'ditolak')      as ditolak,
            count(*) filter (where status = 'pending')      as pending
        ")->first();
        $total = (int) $s->total;
        $disetujui = (int) $s->disetujui;
        $ditolak = (int) $s->ditolak;
        $pending = (int) $s->pending;

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

        // ================= LAB STATS (SEMUA PERIODE) =================
        // Statistik per-lab dihitung lintas SEMUA periode (bukan periode aktif saja)
        // agar rasio antar-lab merepresentasikan keseluruhan riwayat.
        $labDisetujui = \App\Models\Laboratorium::select(
            'laboratorium.nama',
            DB::raw('count(pengajuan.id) as total')
        )
            ->join('judul', 'laboratorium.id', '=', 'judul.laboratorium_id')
            ->join('pengajuan', 'judul.id', '=', 'pengajuan.judul_ditetapkan_id')
            ->where('pengajuan.status', 'disetujui')
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
            ->groupBy('laboratorium.nama')
            ->orderByDesc(DB::raw("count(pengajuan.id)"))
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
