<?php

namespace App\Http\Controllers\Kaprodi;

use App\Http\Controllers\Controller;
use App\Models\Judul;
use App\Models\Pengajuan;

class DashboardController extends Controller
{
    public function index()
    {
        $totalJudul = Judul::count();
        $judulDitawarkan = Judul::where('status_judul', 'ditawarkan')->count();
        $pendingFinal = Pengajuan::where('status_kaprodi', 'pending')->count();
        $totalPengajuan = Pengajuan::count();

        return view('kaprodi.dashboard', [
            'title' => 'Dashboard Kaprodi',
            'totalJudul' => $totalJudul,
            'judulDitawarkan' => $judulDitawarkan,
            'pendingFinal' => $pendingFinal,
            'totalPengajuan' => $totalPengajuan,
        ]);
    }
}
