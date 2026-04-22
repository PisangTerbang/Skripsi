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
        // Get all pengajuan with relations
        $pengajuan = Pengajuan::with(['mahasiswa', 'judul.laboratorium'])
            ->orderBy('created_at', 'desc')
            ->get()
            ->groupBy('judul_id');

        $laboratorium = Laboratorium::all();

        // Stats
        $totalPengajuan = Pengajuan::count();
        $pending = Pengajuan::where('status', 'pending')->count();
        $disetujui = Pengajuan::where('status', 'disetujui')->count();
        $ditolak = Pengajuan::where('status', 'ditolak')->count();

        // Recent pending submissions
        $recentPending = Pengajuan::with(['mahasiswa', 'judul'])
            ->where('status', 'pending')
            ->latest()
            ->take(5)
            ->get();

        return view('dosen.pengajuan', [
            'pengajuan' => $pengajuan,
            'laboratorium' => $laboratorium,
            'totalPengajuan' => $totalPengajuan,
            'pending' => $pending,
            'disetujui' => $disetujui,
            'ditolak' => $ditolak,
            'recentPending' => $recentPending,
            'title' => 'Review Pengajuan Mahasiswa'
        ]);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:disetujui,ditolak',
            'catatan_dosen' => 'nullable|string|max:1000',
            'laboratorium_id' => 'nullable|exists:laboratorium,id'
        ]);

        try {
            DB::transaction(function () use ($request, $id) {

                $pengajuan = Pengajuan::with(['judul', 'mahasiswa'])->findOrFail($id);

                $statusBaru = $request->status;

                // Check if student already has approved title
                if ($statusBaru === 'disetujui') {
                    $sudahPunya = Pengajuan::where('mahasiswa_id', $pengajuan->mahasiswa_id)
                        ->where('status', 'disetujui')
                        ->exists();

                    if ($sudahPunya) {
                        throw new \Exception('Mahasiswa sudah memiliki judul yang disetujui');
                    }
                }

                // Update status and notes
                $pengajuan->status = $statusBaru;
                $pengajuan->catatan_dosen = $request->catatan_dosen;

                // MODE PILIH
                if ($statusBaru === 'disetujui' && $pengajuan->jenis === 'pilih') {

                    if ($pengajuan->judul) {
                        $pengajuan->judul->update([
                            'is_locked' => DB::raw('true')
                        ]);
                    }

                    // Reject other submissions for this title
                    Pengajuan::where('judul_id', $pengajuan->judul_id)
                        ->where('id', '!=', $pengajuan->id)
                        ->update([
                            'status' => 'ditolak',
                            'catatan_dosen' => 'Judul sudah diambil oleh ' . $pengajuan->mahasiswa->name
                        ]);
                }

                // MODE MANDIRI
                if ($statusBaru === 'disetujui' && $pengajuan->jenis === 'mandiri') {

                    if (!$request->laboratorium_id) {
                        throw new \Exception('Laboratorium wajib dipilih untuk judul mandiri');
                    }

                    // Generate kode
                    $lab = Laboratorium::find($request->laboratorium_id);
                    $prefix = strtoupper(substr($lab->nama, 0, 6));
                    $count = Judul::where('laboratorium_id', $request->laboratorium_id)->count();
                    $kode = $prefix . '-' . ($count + 1);

                    // Create new judul
                    $judulBaru = Judul::create([
                        'kode' => $kode,
                        'nama_judul' => $pengajuan->judul_mandiri,
                        'deskripsi' => $pengajuan->deskripsi_mandiri,
                        'laboratorium_id' => $request->laboratorium_id,
                        'dosen_id' => auth()->id(),
                        'aktif' => DB::raw('true'),
                        'is_locked' => DB::raw('true')
                    ]);

                    $pengajuan->judul_id = $judulBaru->id;
                }

                $pengajuan->save();
            });

            return back()->with('success', 'Pengajuan berhasil ' . ($request->status === 'disetujui' ? 'disetujui' : 'ditolak') . '!');

        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }
}
