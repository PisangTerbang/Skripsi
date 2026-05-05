<?php

namespace App\Http\Controllers\Kaprodi;

use App\Http\Controllers\Controller;
use App\Models\Pengajuan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class MonitoringController extends Controller
{
    public function index()
    {
        // Pengajuan yang sudah di-approve dosen, menunggu final kaprodi
        $pendingFinal = Pengajuan::with(['mahasiswa', 'judul.laboratorium', 'judul.dosen'])
            ->where('status', 'disetujui')
            ->where('status_kaprodi', 'pending')
            ->orderBy('updated_at', 'desc')
            ->get();

        // Semua pengajuan untuk monitoring
        $semuaPengajuan = Pengajuan::with(['mahasiswa', 'judul.laboratorium', 'judul.dosen'])
            ->orderBy('created_at', 'desc')
            ->get();

        return view('kaprodi.monitoring', [
            'title' => 'Monitoring & Final Approval',
            'pendingFinal' => $pendingFinal,
            'semuaPengajuan' => $semuaPengajuan,
        ]);
    }

    public function approve(Request $request, $id)
    {
        $request->validate([
            'catatan_kaprodi' => 'nullable|string|max:1000',
        ]);

        $user = Auth::user();
        $pengajuan = Pengajuan::with('judul')->findOrFail($id);

        DB::table('pengajuan')->where('id', $id)->update([
            'status_kaprodi' => 'disetujui',
            'catatan_kaprodi' => $request->catatan_kaprodi,
            'tanggal_kaprodi' => now(),
            'updated_at' => now(),
        ]);

        // Lock judul
        if ($pengajuan->judul_id) {
            DB::table('judul')->where('id', $pengajuan->judul_id)->update([
                'is_locked' => DB::raw('true'),
                'updated_at' => now(),
            ]);
        }

        // Notifikasi ke mahasiswa
        DB::table('aktivitas')->insert([
            'user_id' => $pengajuan->mahasiswa_id,
            'tipe' => 'final_approval',
            'pesan' => 'Pengajuan judul Anda telah mendapat persetujuan final dari Kaprodi. Anda dapat memulai pengerjaan.',
            'is_read' => DB::raw('false'),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return back()->with('success', 'Pengajuan disetujui! Mahasiswa dapat memulai pengerjaan.');
    }

    public function reject(Request $request, $id)
    {
        $request->validate([
            'catatan_kaprodi' => 'required|string|max:1000',
        ]);

        $user = Auth::user();

        DB::table('pengajuan')->where('id', $id)->update([
            'status_kaprodi' => 'ditolak',
            'catatan_kaprodi' => $request->catatan_kaprodi,
            'tanggal_kaprodi' => now(),
            'updated_at' => now(),
        ]);

        // Notifikasi ke mahasiswa
        $pengajuan = Pengajuan::findOrFail($id);
        DB::table('aktivitas')->insert([
            'user_id' => $pengajuan->mahasiswa_id,
            'tipe' => 'penolakan',
            'pesan' => 'Pengajuan judul Anda ditolak oleh Kaprodi. catatan: ' . $request->catatan_kaprodi,
            'is_read' => DB::raw('false'),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return back()->with('success', 'Pengajuan ditolak dan dikembalikan.');
    }
}
