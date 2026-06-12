<?php

namespace App\Http\Controllers\KoorTA;

use App\Http\Controllers\Controller;
use App\Models\Pengajuan;
use App\Models\Judul;
use App\Models\User;
use App\Models\Periode;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        // Periode aktif
        $periodeAktif = Periode::where('is_active', DB::raw('true'))->first();
        $pid = $periodeAktif?->id;

        // Stats utama — total mahasiswa/dosen/judul bersifat katalog/identitas (global).
        $totalMahasiswa = User::where('role', 'mahasiswa')->count();
        $totalDosen = User::where('role', 'dosen')->count();
        $totalJudul = Judul::count();

        // Pengajuan & status di-scope ke periode aktif (aktivitas berjalan).
        // Histori lintas periode tersedia di chart per-periode & menu Monitoring.
        $totalPengajuan = Pengajuan::where('periode_id', $pid)->count();
        $pengajuanPending = Pengajuan::where('periode_id', $pid)->whereNull('status_kalab')->count();
        $pengajuanProses = Pengajuan::where('periode_id', $pid)
            ->where('status_kalab', 'disetujui')
            ->whereNull('status_kaprodi')->count();
        $pengajuanSelesai = Pengajuan::where('periode_id', $pid)
            ->where('status_kaprodi', 'disetujui')->count();
        $pengajuanDitolak = Pengajuan::where('periode_id', $pid)
            ->where(function ($q) {
                $q->where('status_kalab', 'ditolak')
                    ->orWhere('status_kaprodi', 'ditolak');
            })->count();

        // Pengajuan per periode (untuk chart)
        $pengajuanPerPeriode = Periode::withCount('pengajuan')
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();

        // User terbaru
        $userTerbaru = User::whereIn('role', ['mahasiswa', 'dosen'])
            ->latest()
            ->take(5)
            ->get();

        // Pengajuan terbaru
        $pengajuanTerbaru = Pengajuan::with(['mahasiswa', 'periode', 'judulDitetapkan'])
            ->latest()
            ->take(5)
            ->get();

        return view('koor-ta.dashboard', compact(
            'totalMahasiswa',
            'totalDosen',
            'totalPengajuan',
            'totalJudul',
            'pengajuanPending',
            'pengajuanProses',
            'pengajuanSelesai',
            'pengajuanDitolak',
            'periodeAktif',
            'pengajuanPerPeriode',
            'userTerbaru',
            'pengajuanTerbaru',
        ));
    }
}
