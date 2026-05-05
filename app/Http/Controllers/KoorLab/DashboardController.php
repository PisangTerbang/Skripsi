<?php

namespace App\Http\Controllers\KoorLab;

use App\Http\Controllers\Controller;
use App\Models\Judul;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $labId = $user->laboratorium_id;

        $totalJudul = Judul::where('laboratorium_id', $labId)->count();
        $pendingKoor = Judul::where('status_judul', 'pending_koor')->count();
        $sudahDikelompokkan = Judul::where('laboratorium_id', $labId)
            ->where('status_judul', 'pending_kalab')
            ->count();

        return view('koor-lab.dashboard', [
            'title' => 'Dashboard Koordinator Lab',
            'totalJudul' => $totalJudul,
            'pendingKoor' => $pendingKoor,
            'sudahDikelompokkan' => $sudahDikelompokkan,
        ]);
    }
}
