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
        $pending = Pengajuan::where('periode_id', $periodeAktif->id)
            ->where('status', 'pending')
            ->count();
        $total = Pengajuan::where('periode_id', $periodeAktif->id)->count();
        $disetujui = Pengajuan::where('periode_id', $periodeAktif->id)
            ->where('status', 'disetujui')
            ->count();
        $ditolak = Pengajuan::where('periode_id', $periodeAktif->id)
            ->where('status', 'ditolak')
            ->count();
        $totalSemua = max($total, 1); // cegah bagi 0

        $persenPending = round(($pending / $totalSemua) * 100);
        $persenSetuju = round(($disetujui / $totalSemua) * 100);
        $persenTolak = round(($ditolak / $totalSemua) * 100);
        $trenPengajuan = Periode::select(
            'semester',
            'tahun_ajaran',
            DB::raw('count(pengajuan.id) as total')
        )
            ->leftJoin('pengajuan', 'periode.id', '=', 'pengajuan.periode_id')
            ->groupBy('semester', 'tahun_ajaran')
            ->orderBy('tahun_ajaran')
            ->get();

        $trenKeputusan = Periode::select(
            'semester',
            'tahun_ajaran',
            DB::raw("count(case when pengajuan.status = 'disetujui' then 1 end) as disetujui"),
            DB::raw("count(case when pengajuan.status = 'ditolak' then 1 end) as ditolak")
        )
            ->leftJoin('pengajuan', 'periode.id', '=', 'pengajuan.periode_id')
            ->groupBy('semester', 'tahun_ajaran')
            ->orderBy('tahun_ajaran')
            ->get();

        $labDisetujui = \App\Models\Laboratorium::select(
            'laboratorium.nama',
            DB::raw('count(pengajuan.id) as total')
        )
            ->join('judul', 'laboratorium.id', '=', 'judul.laboratorium_id')
            ->join('pengajuan', 'judul.id', '=', 'pengajuan.judul_id')
            ->where('pengajuan.status', 'disetujui')
            ->where('pengajuan.periode_id', $periodeAktif->id)
            ->groupBy('laboratorium.nama')
            ->orderByDesc('total')
            ->take(5)
            ->get();

        $labDitolak = \App\Models\Laboratorium::select(
            'laboratorium.nama',
            DB::raw('count(pengajuan.id) as total')
        )
            ->join('judul', 'laboratorium.id', '=', 'judul.laboratorium_id')
            ->join('pengajuan', 'judul.id', '=', 'pengajuan.judul_id')
            ->where('pengajuan.status', 'ditolak')
            ->where('pengajuan.periode_id', $periodeAktif->id)
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
            ->join('pengajuan', 'judul.id', '=', 'pengajuan.judul_id')
            ->where('pengajuan.periode_id', $periodeAktif->id)
            ->groupBy('laboratorium.nama')
            ->get();

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
            'title' => 'Dashboard Dosen'
        ]);
    }
}
