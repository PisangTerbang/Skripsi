<?php

namespace App\Http\Controllers\Dosen;

use App\Http\Controllers\Controller;
use App\Models\Pengajuan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DosenPengajuanController extends Controller
{
    public function index()
    {
        $pengajuan = Pengajuan::with(['mahasiswa', 'judul'])
            ->orderBy('judul_id')
            ->get()
            ->groupBy('judul_id');

        return view('dosen.pengajuan', [
            'pengajuan' => $pengajuan,
            'title' => 'Pengajuan Judul Mahasiswa'
        ]);
    }

    public function update(Request $request, $id)
    {
        DB::transaction(function () use ($request, $id) {

            $pengajuan = Pengajuan::with(['judul', 'mahasiswa'])->findOrFail($id);
            $statusBaru = $request->status;

            // ❌ Cegah double-approve untuk judul yang sama
            if ($statusBaru === 'disetujui' && $pengajuan->judul_id) {
                $sudahAda = Pengajuan::where('judul_id', $pengajuan->judul_id)
                    ->where('status', 'disetujui')
                    ->exists();

                if ($sudahAda) {
                    throw new \Exception('Judul sudah diambil mahasiswa lain.');
                }
            }

            // 🔥 HANDLE MANDIRI → CONVERT KE JUDUL
            if ($statusBaru === 'disetujui' && $pengajuan->jenis === 'mandiri') {

                // 🛑 Idempotent guard: jika sudah pernah di-convert, jangan buat lagi
                if (!$pengajuan->judul_id) {

                    $judulBaru = Judul::create([
                        'nama_judul' => $pengajuan->judul_mandiri,
                        'deskripsi' => $pengajuan->deskripsi_mandiri,
                        'dosen_id' => auth()->id(), // dosen yang approve
                        'laboratorium_id' => 1,     // sementara default (bisa kamu ganti nanti)
                        'aktif' => true
                    ]);

                    // 🔗 hubungkan pengajuan ke judul baru
                    $pengajuan->judul_id = $judulBaru->id;
                }
            }

            // 🔄 Update status pengajuan
            $pengajuan->status = $statusBaru;
            $pengajuan->catatan_dosen = $request->catatan_dosen;
            $pengajuan->save();

            // 🔥 Jika disetujui → tolak semua pengajuan lain untuk judul yang sama
            if ($statusBaru === 'disetujui' && $pengajuan->judul_id) {

                Pengajuan::where('judul_id', $pengajuan->judul_id)
                    ->where('id', '!=', $pengajuan->id)
                    ->update([
                        'status' => 'ditolak',
                        'catatan_dosen' => 'Judul sudah diambil oleh ' . $pengajuan->mahasiswa->name
                    ]);
            }
        });

        return back()->with('success', 'Status pengajuan diperbarui');
    }
}