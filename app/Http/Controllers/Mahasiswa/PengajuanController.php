<?php

namespace App\Http\Controllers\Mahasiswa;

use App\Http\Controllers\Controller;
use App\Models\Judul;
use App\Models\Pengajuan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PengajuanController extends Controller
{
    public function index()
    {
        $judul = Judul::whereRaw('aktif = true')
            ->withCount('pengajuan')
            ->with(['laboratorium', 'dosen'])
            ->get();

        return view('Mahasiswa.pengajuan', [
            'judul' => $judul,
            'title' => 'Pengajuan'
        ]);
    }

    public function store(Request $request)
    {
        Pengajuan::create([
            'mahasiswa_id' => Auth::id(),
            'judul_id' => $request->judul_id,
            'jenis' => 'pilih',
            'prioritas' => $request->prioritas,
            'alasan' => $request->alasan
        ]);

        return back()->with('success', 'Pengajuan berhasil dikirim');
    }
}
