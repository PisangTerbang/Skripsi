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
        // Stats utama
        $totalMahasiswa = User::where('role', 'mahasiswa')->count();
        $totalDosen = User::where('role', 'dosen')->count();
        $totalPengajuan = Pengajuan::count();
        $totalJudul = Judul::count();

        // Pengajuan per status
        $pengajuanPending = Pengajuan::whereNull('status_kalab')->count();
        $pengajuanProses = Pengajuan::where('status_kalab', 'disetujui')
            ->whereNull('status_kaprodi')->count();
        $pengajuanSelesai = Pengajuan::where('status_kaprodi', 'disetujui')->count();
        $pengajuanDitolak = Pengajuan::where('status_kalab', 'ditolak')
            ->orWhere('status_kaprodi', 'ditolak')->count();

        // Periode aktif
        $periodeAktif = Periode::where('is_active', DB::raw('true'))->first();

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
