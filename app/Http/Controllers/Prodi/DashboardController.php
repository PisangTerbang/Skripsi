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

        $totalJudul = Judul::count();
        $judulDitawarkan = Judul::where('status_judul', 'ditawarkan')->count();
        $pendingFinal = Pengajuan::where('status_kaprodi', 'pending')->count();
        $totalPengajuan = Pengajuan::count();

        return view('prodi.dashboard', [
            'title' => 'Dashboard Program Studi',
            'totalJudul' => $totalJudul,
            'judulDitawarkan' => $judulDitawarkan,
            'pendingFinal' => $pendingFinal,
            'totalPengajuan' => $totalPengajuan,
        ]);
    }
}
