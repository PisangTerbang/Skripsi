<?php

namespace App\Http\Controllers\Dosen;

use App\Http\Controllers\Controller;
use App\Models\Judul;
use App\Models\Laboratorium;
use Illuminate\Http\Request;

class DosenJudulController extends Controller
{
    public function index()
    {
        $laboratorium = Laboratorium::all();
        $judul = Judul::latest()->get();

        return view('dosen.judul', [
            'judul' => $judul,
            'laboratorium' => $laboratorium,
            'title' => 'Manajemen Judul'
        ]);
    }

    public function store(Request $request)
    {
        Judul::create([
            'laboratorium_id' => $request->laboratorium_id,
            'dosen_id' => 1,
            'nama_judul' => $request->nama_judul,
            'deskripsi' => $request->deskripsi
        ]);

        return back();
    }

    public function destroy($id)
    {
        Judul::findOrFail($id)->delete();
        return back();
    }
}
