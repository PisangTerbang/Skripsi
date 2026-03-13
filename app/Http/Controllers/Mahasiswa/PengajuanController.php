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
        $mahasiswaId = Auth::id();

        $judul = Judul::whereRaw('aktif = true')
            ->withCount('pengajuan')
            ->with(['laboratorium', 'dosen'])
            ->get();

        $jumlahPengajuan = Pengajuan::where('mahasiswa_id', $mahasiswaId)->count();

        return view('Mahasiswa.pengajuan', [
            'judul' => $judul,
            'title' => 'Pengajuan',
            'jumlahPengajuan' => $jumlahPengajuan
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'judul_id' => 'required',
            'prioritas' => 'required|integer|min:1|max:2',
            'alasan' => 'nullable|string'
        ]);

        $mahasiswaId = Auth::id();

        $jumlahPengajuan = Pengajuan::where('mahasiswa_id', $mahasiswaId)->count();

        if ($jumlahPengajuan >= 2) {
            return back()->with('error', 'Anda hanya boleh mengajukan maksimal 2 judul.');
        }

        $prioritasDipakai = Pengajuan::where('mahasiswa_id', $mahasiswaId)
            ->where('prioritas', $request->prioritas)
            ->exists();

        if ($prioritasDipakai) {
            return back()->with('error', 'Prioritas tersebut sudah digunakan.');
        }

        Pengajuan::create([
            'mahasiswa_id' => $mahasiswaId,
            'judul_id' => $request->judul_id,
            'jenis' => 'pilih',
            'prioritas' => $request->prioritas,
            'alasan' => $request->alasan
        ]);

        return back()->with('success', 'Pengajuan berhasil dikirim');
    }
}