<?php

namespace App\Http\Controllers\KepalaLab;

use App\Http\Controllers\Controller;
use App\Models\Judul;
use App\Models\Laboratorium;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ValidasiController extends Controller
{
    public function index()
    {
        $judulPending = Judul::with(['laboratorium', 'dosen', 'koorLab'])
            ->where('status_judul', 'pending_kalab')
            ->orderBy('created_at', 'desc')
            ->get();

        $judulSelesai = Judul::with(['laboratorium', 'dosen'])
            ->whereIn('status_judul', ['ditawarkan', 'ditolak_kalab'])
            ->orderBy('updated_at', 'desc')
            ->take(20)
            ->get();

        $laboratorium = Laboratorium::all();

        return view('kepala-lab.validasi', [
            'title' => 'Validasi Judul',
            'judulPending' => $judulPending,
            'judulSelesai' => $judulSelesai,
            'laboratorium' => $laboratorium,
        ]);
    }

    public function approve(Request $request, $id)
    {
        $request->validate([
            'catan_kalab' => 'nullable|string|max:1000',
        ]);

        $user = Auth::user();

        DB::table('judul')->where('id', $id)->update([
            'status_judul' => 'ditawarkan',
            'catatan_kalab' => $request->catatan_kalab,
            'tanggal_kalab' => now(),
            'aktif' => DB::raw('true'),
            'updated_at' => now(),
        ]);

        DB::table('judul_logs')->insert([
            'judul_id' => $id,
            'user_id' => $user->id,
            'aksi' => 'divalidasi',
            'dari_status' => 'pending_kalab',
            'ke_status' => 'ditawarkan',
            'catatan' => $request->catatan_kalab,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return back()->with('success', 'Judul berhasil divalidasi dan ditawarkan!');
    }

    public function reject(Request $request, $id)
    {
        $request->validate([
            'catatan_kalab' => 'required|string|max:1000',
        ]);

        $user = Auth::user();

        DB::table('judul')->where('id', $id)->update([
            'status_judul' => 'ditolak_kalab',
            'catatan_kalab' => $request->catatan_kalab,
            'tanggal_kalab' => now(),
            'updated_at' => now(),
        ]);

        DB::table('judul_logs')->insert([
            'judul_id' => $id,
            'user_id' => $user->id,
            'aksi' => 'ditolak_kalab',
            'dari_status' => 'pending_kalab',
            'ke_status' => 'ditolak_kalab',
            'catatan' => $request->catatan_kalab,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return back()->with('success', 'Judul dikembalikan ke Koordinator Lab.');
    }
}
