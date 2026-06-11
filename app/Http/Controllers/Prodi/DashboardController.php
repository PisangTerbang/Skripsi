<?php

namespace App\Http\Controllers\Prodi;

use App\Http\Controllers\Controller;
use App\Models\Judul;
use App\Models\Pengajuan;

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        // Validasi role
        if ($user->role !== 'prodi') {
            abort(403, 'Anda tidak memiliki akses sebagai Program Studi');
        }

        // Pengajuan yang menunggu keputusan Prodi pada PERIODE AKTIF (sudah disetujui Ka Lab, belum diputus Prodi).
        // status_kaprodi bernilai null saat menunggu (bukan 'pending'), jadi pakai whereNull.
        $pid = \App\Models\Periode::periodeAktif()?->id;

        $totalJudul = Judul::count();
        $judulDitawarkan = Judul::where('status_judul', 'ditawarkan')->count();
        $pendingFinal = Pengajuan::where('periode_id', $pid)
            ->where('status_kalab', 'disetujui')
            ->whereNull('status_kaprodi')
            ->count();
        $totalPengajuan = Pengajuan::where('periode_id', $pid)->count();

        return view('prodi.dashboard', [
            'title' => 'Dashboard Program Studi',
            'totalJudul' => $totalJudul,
            'judulDitawarkan' => $judulDitawarkan,
            'pendingFinal' => $pendingFinal,
            'totalPengajuan' => $totalPengajuan,
        ]);
    }
}
