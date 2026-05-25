<?php

namespace App\Http\Controllers\KepalaLab;

use App\Http\Controllers\Controller;
use App\Models\Judul;

class DashboardController extends Controller
{
    public function index()
    {
        $pendingValidasi = Judul::where('status_judul', 'pending_kalab')->count();
        $sudahValidasi = Judul::whereIn('status_judul', ['ditawarkan', 'ditolak_kalab'])->count();
        $totalJudul = Judul::count();

        return view('kepala-lab.dashboard', [
            'title' => 'Dashboard Kepala Lab',
            'pendingValidasi' => $pendingValidasi,
            'sudahValidasi' => $sudahValidasi,
            'totalJudul' => $totalJudul,
        ]);
    }
}
