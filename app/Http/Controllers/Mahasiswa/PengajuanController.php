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

        $judul = Judul::aktif()
            ->with([
                'laboratorium',
                'dosen',
                'pengajuan' => function ($q) {
                    $q->where('status', 'disetujui')
                        ->with('mahasiswa');
                }
            ])
            ->withCount([
                'pengajuan as peminat' => function ($q) {
                    $q->where('jenis', 'pilih');
                }
            ])
            ->get();

        $jumlahPengajuan = Pengajuan::where('mahasiswa_id', $mahasiswaId)->count();

        $pengajuanSaya = Pengajuan::where('mahasiswa_id', $mahasiswaId)
            ->pluck('judul_id')
            ->toArray();

        return view('Mahasiswa.pengajuan', compact(
            'judul',
            'jumlahPengajuan',
            'pengajuanSaya'
        ))->with('title', 'Pengajuan');
    }

    public function store(Request $request)
    {
        $mahasiswaId = Auth::id();

        $request->validate([
            'jenis' => 'required|in:pilih,mandiri',
            'prioritas' => 'required|integer|min:1|max:2',
        ]);

        if (Pengajuan::where('mahasiswa_id', $mahasiswaId)->count() >= 2) {
            return back()->with('error', 'Maksimal 2 pengajuan.');
        }

        if (
            Pengajuan::where('mahasiswa_id', $mahasiswaId)
                ->where('prioritas', $request->prioritas)
                ->exists()
        ) {
            return back()->with('error', 'Prioritas sudah digunakan.');
        }

        // MODE PILIH
        if ($request->jenis === 'pilih') {

            $request->validate([
                'judul_id' => 'required'
            ]);

            if (
                Pengajuan::where('mahasiswa_id', $mahasiswaId)
                    ->where('judul_id', $request->judul_id)
                    ->exists()
            ) {
                return back()->with('error', 'Judul sudah dipilih.');
            }

            $sudahDiambil = Pengajuan::where('judul_id', $request->judul_id)
                ->where('status', 'disetujui')
                ->exists();

            if ($sudahDiambil) {
                return back()->with('error', 'Judul sudah diambil.');
            }

            Pengajuan::create([
                'mahasiswa_id' => $mahasiswaId,
                'judul_id' => $request->judul_id,
                'jenis' => 'pilih',
                'prioritas' => $request->prioritas,
                'alasan' => $request->alasan,
                'status' => 'pending'
            ]);
        }

        // MODE MANDIRI
        if ($request->jenis === 'mandiri') {

            $request->validate([
                'judul_mandiri' => 'required|string|max:255',
                'deskripsi_mandiri' => 'required|string'
            ]);

            Pengajuan::create([
                'mahasiswa_id' => $mahasiswaId,
                'jenis' => 'mandiri',
                'judul_mandiri' => $request->judul_mandiri,
                'deskripsi_mandiri' => $request->deskripsi_mandiri,
                'prioritas' => $request->prioritas,
                'status' => 'pending'
            ]);
        }

        return back()->with('success', 'Pengajuan berhasil dikirim');
    }
}