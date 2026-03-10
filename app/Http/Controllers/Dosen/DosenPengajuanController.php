<?php

namespace App\Http\Controllers\Dosen;

use App\Http\Controllers\Controller;
use App\Models\Pengajuan;
use Illuminate\Http\Request;

class DosenPengajuanController extends Controller
{
    public function index()
    {
        $pengajuan = Pengajuan::latest()->get();

        return view('dosen.pengajuan', [
            'pengajuan' => $pengajuan,
            'title' => 'Pengajuan Judul Mahasiswa'
        ]);
    }
    public function update(Request $request, $id)
    {
        $pengajuan = Pengajuan::findOrFail($id);

        $pengajuan->status = $request->status;
        $pengajuan->catatan_dosen = $request->catatan_dosen;
        $pengajuan->save();

        return back();
    }
}
