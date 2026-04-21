<?php

namespace App\Http\Controllers\Dosen;

use App\Http\Controllers\Controller;
use App\Models\Pengajuan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Laboratorium;
use App\Models\Judul;

class DosenPengajuanController extends Controller
{
    public function index()
    {
        $pengajuan = Pengajuan::with(['mahasiswa', 'judul'])
            ->orderBy('judul_id')
            ->get()
            ->groupBy('judul_id');

        $laboratorium = Laboratorium::all();

        return view('dosen.pengajuan', [
            'pengajuan' => $pengajuan,
            'laboratorium' => $laboratorium,
            'title' => 'Pengajuan Judul Mahasiswa'
        ]);
    }

    public function update(Request $request, $id)
    {
        // 🔥 VALIDASI WAJIB (biar status tidak null)
        $request->validate([
            'status' => 'required|in:disetujui,ditolak',
            'catatan_dosen' => 'nullable|string',
            'laboratorium_id' => 'nullable'
        ]);

        try {
            DB::transaction(function () use ($request, $id) {

                $pengajuan = Pengajuan::with(['judul', 'mahasiswa'])->findOrFail($id);

                $statusBaru = $request->status;

                // 🔥 CEK: mahasiswa sudah punya judul disetujui
                if ($statusBaru === 'disetujui') {
                    $sudahPunya = Pengajuan::where('mahasiswa_id', $pengajuan->mahasiswa_id)
                        ->where('status', 'disetujui')
                        ->exists();

                    if ($sudahPunya) {
                        throw new \Exception('Mahasiswa sudah memiliki judul yang disetujui');
                    }
                }

                // 🔥 SET STATUS (INI YANG PENTING)
                $pengajuan->status = $statusBaru;
                $pengajuan->catatan_dosen = $request->catatan_dosen;

                // =============================
                // MODE PILIH
                // =============================
                if ($statusBaru === 'disetujui' && $pengajuan->jenis === 'pilih') {

                    if ($pengajuan->judul) {
                        $pengajuan->judul->update([
                            'is_locked' => true
                        ]);
                    }

                    Pengajuan::where('judul_id', $pengajuan->judul_id)
                        ->where('id', '!=', $pengajuan->id)
                        ->update([
                            'status' => 'ditolak',
                            'catatan_dosen' => 'Judul sudah diambil oleh ' . $pengajuan->mahasiswa->name
                        ]);
                }

                // =============================
                // MODE MANDIRI
                // =============================
                if ($statusBaru === 'disetujui' && $pengajuan->jenis === 'mandiri') {

                    if (!$request->laboratorium_id) {
                        throw new \Exception('Laboratorium wajib dipilih');
                    }

                    $judulBaru = Judul::create([
                        'nama_judul' => $pengajuan->judul_mandiri,
                        'deskripsi' => $pengajuan->deskripsi_mandiri,
                        'laboratorium_id' => $request->laboratorium_id,
                        'dosen_id' => auth()->id(),
                        'is_locked' => true
                    ]);

                    $pengajuan->judul_id = $judulBaru->id;
                }

                // 🔥 SAVE FINAL
                $pengajuan->save();
            });

            return back()->with('success', 'Status berhasil diperbarui');

        } catch (\Exception $e) {

            // 🔥 BIAR KAMU LIHAT ERROR SEBENARNYA
            return back()->with('error', $e->getMessage());
        }
    }
}