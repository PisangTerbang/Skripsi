<?php

namespace App\Http\Controllers\Mahasiswa;

use App\Http\Controllers\Controller;
use App\Models\Judul;
use App\Models\Pengajuan;
use App\Models\Laboratorium;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PengajuanController extends Controller
{
    public function index()
    {
        $mahasiswaId = Auth::id();

        // Get all active judul with relations
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

        // Count active submissions
        $jumlahPengajuan = Pengajuan::where('mahasiswa_id', $mahasiswaId)
            ->whereIn('status', ['pending', 'disetujui'])
            ->count();

        // Get submitted judul IDs
        $pengajuanSaya = Pengajuan::where('mahasiswa_id', $mahasiswaId)
            ->pluck('judul_id')
            ->toArray();

        // Get all labs for filter
        $laboratorium = Laboratorium::all();

        // Get my submissions for display
        $mySubmissions = Pengajuan::with(['judul', 'dosenPilihan'])
            ->where('mahasiswa_id', $mahasiswaId)
            ->whereIn('status', ['pending', 'disetujui'])
            ->get();

        return view('mahasiswa.pengajuan', compact(
            'judul',
            'jumlahPengajuan',
            'pengajuanSaya',
            'laboratorium',
            'mySubmissions'
        ))->with('title', 'Pengajuan Judul');
    }

    public function store(Request $request)
    {
        $mahasiswaId = Auth::id();

        $request->validate([
            'jenis' => 'required|in:pilih,mandiri',
            'prioritas' => 'required|integer|min:1|max:2',
        ]);

        // Check slot limit
        $jumlahAktif = Pengajuan::where('mahasiswa_id', $mahasiswaId)
            ->whereIn('status', ['pending', 'disetujui'])
            ->count();

        if ($jumlahAktif >= 2) {
            return back()->with('error', 'Maksimal 2 pengajuan aktif.');
        }

        // Check priority conflict
        $prioritasTerpakai = Pengajuan::where('mahasiswa_id', $mahasiswaId)
            ->whereIn('status', ['pending', 'disetujui'])
            ->where('prioritas', $request->prioritas)
            ->exists();

        if ($prioritasTerpakai) {
            return back()->with('error', 'Prioritas ' . $request->prioritas . ' sudah digunakan.');
        }

        // MODE PILIH
        if ($request->jenis === 'pilih') {

            $request->validate([
                'judul_id' => 'required|exists:judul,id',
                'alasan' => 'nullable|string|max:500'
            ]);

            // Check if already selected
            $sudahDipilih = Pengajuan::where('mahasiswa_id', $mahasiswaId)
                ->whereIn('status', ['pending', 'disetujui'])
                ->where('judul_id', $request->judul_id)
                ->exists();

            if ($sudahDipilih) {
                return back()->with('error', 'Anda sudah mengajukan judul ini.');
            }

            // Check if already taken
            $sudahDiambil = Pengajuan::where('judul_id', $request->judul_id)
                ->where('status', 'disetujui')
                ->exists();

            if ($sudahDiambil) {
                return back()->with('error', 'Judul sudah diambil mahasiswa lain.');
            }

            Pengajuan::create([
                'mahasiswa_id' => $mahasiswaId,
                'judul_id' => $request->judul_id,
                'jenis' => 'pilih',
                'prioritas' => $request->prioritas,
                'alasan' => $request->alasan,
                'status' => 'pending'
            ]);

            return back()->with('success', 'Pengajuan judul berhasil dikirim! Menunggu review dosen.');
        }

        // MODE MANDIRI
        if ($request->jenis === 'mandiri') {

            $request->validate([
                'judul_mandiri' => 'required|string|max:255',
                'deskripsi_mandiri' => 'required|string|max:1000'
            ]);

            Pengajuan::create([
                'mahasiswa_id' => $mahasiswaId,
                'jenis' => 'mandiri',
                'judul_mandiri' => $request->judul_mandiri,
                'deskripsi_mandiri' => $request->deskripsi_mandiri,
                'prioritas' => $request->prioritas,
                'status' => 'pending'
            ]);

            return back()->with('success', 'Judul mandiri berhasil diajukan! Menunggu review dosen.');
        }

        return back()->with('error', 'Terjadi kesalahan.');
    }

    public function riwayat()
    {
        $mahasiswaId = Auth::id();

        $pengajuan = Pengajuan::with(['judul.laboratorium', 'dosenPilihan'])
            ->where('mahasiswa_id', $mahasiswaId)
            ->latest()
            ->get();

        return view('mahasiswa.riwayat', [
            'pengajuan' => $pengajuan,
            'title' => 'Riwayat Pengajuan'
        ]);
    }
}
