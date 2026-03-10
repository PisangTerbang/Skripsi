<?php

namespace App\Http\Controllers\Mahasiswa;

use App\Http\Controllers\Controller;
use App\Models\Aktivitas;
use App\Models\Judul;
use App\Models\Laboratorium;
use App\Models\Pengajuan;
use App\Models\Periode;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class MahasiswaDashboardController extends Controller
{
    public function index()
    {
        $periodeAktif = Periode::periodeAktif();
        // Judul terbaru
        $judulTerbaru = Judul::latest()->take(3)->get();

        // Total pengajuan
        $totalPengajuan = Pengajuan::where('periode_id', $periodeAktif->id)->count();

        // Aktivitas mahasiswa
        $aktivitas = Aktivitas::where('user_id', Auth::id())
            ->latest()
            ->take(5)
            ->get();

        // Lab populer berdasarkan jumlah pengajuan
        $labPopuler = Laboratorium::select(
            'laboratorium.nama',
            DB::raw('count(pengajuan.id) as total_pengajuan')
        )
            ->join('judul', 'laboratorium.id', '=', 'judul.laboratorium_id')
            ->join('pengajuan', 'judul.id', '=', 'pengajuan.judul_id')
            ->where('pengajuan.periode_id', $periodeAktif->id)
            ->groupBy('laboratorium.nama')
            ->orderByDesc('total_pengajuan')
            ->get();

        // Total semua pengajuan lab
        $totalLabPengajuan = $labPopuler->sum('total_pengajuan');

        // Hitung persentase minat per lab
        $labPopuler = $labPopuler->map(function ($lab) use ($totalLabPengajuan) {

            $lab->persentase = $totalLabPengajuan > 0
                ? round(($lab->total_pengajuan / $totalLabPengajuan) * 100)
                : 0;

            return $lab;
        });

        return view('Mahasiswa.beranda', [
            'aktivitas' => $aktivitas,
            'labPopuler' => $labPopuler,
            'judulTerbaru' => $judulTerbaru,
            'totalPengajuan' => $totalPengajuan,
            'periodeAktif' => $periodeAktif,
            'title' => 'Beranda'
        ]);
    }
}