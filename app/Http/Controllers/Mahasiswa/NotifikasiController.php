<?php

namespace App\Http\Controllers\Mahasiswa;

use App\Http\Controllers\Controller;
use App\Models\Aktivitas;
use Illuminate\Support\Facades\Auth;

class NotifikasiController extends Controller
{
    public function index()
    {
        $aktivitas = Aktivitas::where('user_id', 2)
            ->latest()
            ->get();

        return view('Mahasiswa.notifikasi', [
            'aktivitas' => $aktivitas,
            'title' => 'Notifikasi'
        ]);
    }
}
