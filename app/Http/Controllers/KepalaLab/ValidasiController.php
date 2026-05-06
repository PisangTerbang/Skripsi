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

        // NOTIFIKASI KE DOSEN (pemilik judul)
        $judul = \App\Models\Judul::find($id);
        if ($judul && $judul->dosen_id) {
            DB::table('aktivitas')->insert([
                'user_id' => $judul->dosen_id,
                'tipe' => 'judul_divalidasi',
                'pesan' => 'Judul "' . $judul->nama_judul . '" telah divalidasi dan ditawarkan ke mahasiswa.',
                'is_read' => DB::raw('false'),
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        // NOTIFIKASI KE KOOR LAB yang mengelompokan
        if ($judul && $judul->koor_lab_id) {
            DB::table('aktivitas')->insert([
                'user_id' => $judul->koor_lab_id,
                'tipe' => 'judul_divalidasi',
                'pesan' => 'Judul "' . $judul->nama_judul . '" yang Anda kelompokan telah divalidasi Kepala Lab.',
                'is_read' => DB::raw('false'),
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }


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

        // NOTIFIKASI KE KOOR LAB
        $judul = \App\Models\Judul::find($id);
        if ($judul && $judul->koor_lab_id) {
            DB::table('aktivitas')->insert([
                'user_id' => $judul->koor_lab_id,
                'tipe' => 'judul_ditolak',
                'pesan' => 'Judul "' . $judul->nama_judul . '" ditolak oleh Kepala Lab. Catatan: ' . $request->catan_kalab,
                'is_read' => DB::raw('false'),
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        // NOTIFIKASI KE DOSEN
        if ($judul && $judul->dosen_id) {
            DB::table('aktivitas')->insert([
                'user_id' => $judul->dosen_id,
                'tipe' => 'judul_ditolak',
                'pesan' => 'Judul "' . $judul->nama_judul . '" ditolak oleh Kepala Lab. Catatan: ' . $request->catatan_kalab,
                'is_read' => DB::raw('false'),
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }


        return back()->with('success', 'Judul dikembalikan ke Koordinator Lab.');
    }
}
