<?php

namespace App\Http\Controllers\Dosen;

use App\Http\Controllers\Controller;
use App\Models\Judul;
use App\Models\Laboratorium;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class DosenJudulController extends Controller
{
    public function index()
    {
        $laboratorium = Laboratorium::all();

        // 🔥 hanya ambil judul milik dosen login
        $judul = Judul::where('dosen_id', Auth::id())
            ->latest()
            ->get();

        return view('dosen.judul', [
            'judul' => $judul,
            'laboratorium' => $laboratorium,
            'title' => 'Manajemen Judul'
        ]);
    }
    private function generateKode($laboratorium_id)
    {
        $lab = \App\Models\Laboratorium::find($laboratorium_id);

        if (!$lab)
            return null;

        // ambil prefix (misal: SIRKEL)
        $prefix = strtoupper(substr($lab->nama, 0, 6)); // bisa disesuaikan

        // hitung jumlah judul di lab tersebut
        $count = \App\Models\Judul::where('laboratorium_id', $laboratorium_id)->count();

        return $prefix . '-' . ($count + 1);
    }

    public function store(Request $request)
    {
        // ✅ VALIDASI
        $request->validate([
            'laboratorium_id' => 'required',
            'nama_judul' => 'required|string|max:255',
            'deskripsi' => 'nullable|string'
        ]);

        $kode = $this->generateKode($request->laboratorium_id);

        Judul::create([
            'laboratorium_id' => $request->laboratorium_id,
            'dosen_id' => Auth::id(),
            'kode' => $kode, // 🔥 TAMBAHAN
            'nama_judul' => $request->nama_judul,
            'deskripsi' => $request->deskripsi,
            'aktif' => true,
            'is_locked' => false   
        ]);

        return back()->with('success', 'Judul berhasil ditambahkan');
    }

    public function destroy($id)
    {
        $judul = Judul::where('id', $id)
            ->where('dosen_id', Auth::id())
            ->firstOrFail();

        $judul->delete();

        return back()->with('success', 'Judul berhasil dihapus');
    }
}