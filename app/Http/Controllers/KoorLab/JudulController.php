<?php

namespace App\Http\Controllers\KoorLab;

use App\Http\Controllers\Controller;
use App\Models\Judul;
use App\Models\Laboratorium;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class JudulController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        // Judul yang perlu dikelompokkan (status: pending_koor)
        $judulPending = Judul::with(['laboratorium', 'dosen'])
            ->where('status_judul', 'pending_koor')
            ->orderBy('created_at', 'desc')
            ->get();

        // Judul yang sudah dikelompokkan oleh koor ini
        $judulSelesai = Judul::with(['laboratorium', 'dosen'])
            ->where('koor_lab_id', $user->id)
            ->whereIn('status_judul', ['pending_kalab', 'ditawarkan'])
            ->orderBy('created_at', 'desc')
            ->get();

        $laboratorium = Laboratorium::all();

        return view('koor-lab.judul', [
            'title' => 'Kelompokan Judul',
            'judulPending' => $judulPending,
            'judulSelesai' => $judulSelesai,
            'laboratorium' => $laboratorium,
        ]);
    }

    public function kelompokkan(Request $request, $id)
    {
        $request->validate([
            'laboratorium_id' => 'required|exists:laboratorium,id',
            'catatan_koor' => 'nullable|string|max:1000',
        ]);

        $user = Auth::user();
        $judul = Judul::findOrFail($id);

        DB::table('judul')->where('id', $id)->update([
            'laboratorium_id' => $request->laboratorium_id,
            'status_judul' => 'pending_kalab',
            'catatan_koor' => $request->catatan_koor,
            'koor_lab_id' => $user->id,
            'tanggal_koor' => now(),
            'updated_at' => now(),
        ]);

        // Log
        DB::table('judul_logs')->insert([
            'judul_id' => $id,
            'user_id' => $user->id,
            'aksi' => 'dikelompokkan',
            'dari_status' => 'pending_koor',
            'ke_status' => 'pending_kalab',
            'catatan' => $request->catatan_koor,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        // NOTIFIKASI KE KEPALA LAB
        $kepalaLab = \App\Models\User::where('role', 'kepala_lab')->first();
        if ($kepalaLab) {
            DB::table('aktivitas')->insert([
                'user_id' => $kepalaLab->id,
                'tipe' => 'judul_dikelompokkan',
                'pesan' => 'Judul "' . $judul->nama_judul . '" telah dikelompokan ke lab ' . $request->laboratorium_id . ' oleh ' . $user->name,
                'is_read' => DB::raw('false'),
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        // NOTIFIKASI KE DOSEN (pemilik judul)
        if ($judul->dosen_id) {
            DB::table('aktivitas')->insert([
                'user_id' => $judul->dosen_id,
                'tipe' => 'judul_dikelompokkan',
                'pesan' => 'Judul "' . $judul->nama_judul . '" sedang diproses oleh Koordinator Lab.',
                'is_read' => DB::raw('false'),
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        return back()->with('success', 'Judul berhasil dikelompokkan!');
    }
}
