<?php

namespace App\Http\Controllers\Mahasiswa;

use App\Http\Controllers\Controller;
use App\Models\Judul;
use App\Models\Pengajuan;
use App\Models\Aktivitas;
use App\Models\Laboratorium;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class PengajuanController extends Controller
{
    public function index()
    {
        $mahasiswaId = Auth::id();

        // Hanya tampilkan judul yang sudah berstatus 'ditawarkan' (los validasi Kepala Lab)
        $judul = Judul::where('status_judul', 'ditawarkan')
            ->where('is_locked', '=', DB::raw('false'))
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

        $jumlahPengajuan = Pengajuan::where('mahasiswa_id', $mahasiswaId)
            ->whereIn('status', ['pending', 'disetujui'])
            ->count();

        $pengajuanSaya = Pengajuan::where('mahasiswa_id', $mahasiswaId)
            ->pluck('judul_id')
            ->toArray();

        $laboratorium = Laboratorium::all();

        $mySubmissions = Pengajuan::with(['judul.laboratorium', 'dosenPilihan'])
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
        $mahasiswaName = Auth::user()->name;

        $request->validate([
            'jenis' => 'required|in:pilih,mandiri',
            'prioritas' => 'required|integer|min:1|max:2',
        ]);

        $jumlahAktif = Pengajuan::where('mahasiswa_id', $mahasiswaId)
            ->whereIn('status', ['pending', 'disetujui'])
            ->count();

        if ($jumlahAktif >= 2) {
            return back()->with('error', 'Maksimal 2 pengajuan aktif.');
        }

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

            $sudahDipilih = Pengajuan::where('mahasiswa_id', $mahasiswaId)
                ->whereIn('status', ['pending', 'disetujui'])
                ->where('judul_id', $request->judul_id)
                ->exists();

            if ($sudahDipilih) {
                return back()->with('error', 'Anda sudah mengajukan judul ini.');
            }

            $sudahDiambil = Pengajuan::where('judul_id', $request->judul_id)
                ->where('status', 'disetujui')
                ->exists();

            if ($sudahDiambil) {
                return back()->with('error', 'Judul sudah diambil mahasiswa lain.');
            }

            $pengajuan = Pengajuan::create([
                'mahasiswa_id' => $mahasiswaId,
                'judul_id' => $request->judul_id,
                'jenis' => 'pilih',
                'prioritas' => $request->prioritas,
                'alasan' => $request->alasan,
                'status' => 'pending'
            ]);

            // Notifikasi ke dosen pemilik judul
            $judul = Judul::find($request->judul_id);
            if ($judul && $judul->dosen_id) {
                DB::table('aktivitas')->insert([
                    'user_id' => $judul->dosen_id,
                    'tipe' => 'pengajuan_baru',
                    'pesan' => $mahasiswaName . ' mengajukan judul: ' . $judul->nama_judul,
                    'is_read' => DB::raw('false'),
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }

            return back()->with('success', 'Pengajuan judul berhasil dikirim! Menunggu review dosen.');
        }

        // MODE MANDIRI
        if ($request->jenis === 'mandiri') {

            $request->validate([
                'judul_mandiri' => 'required|string|max:255',
                'deskripsi_mandiri' => 'required|string|max:1000'
            ]);

            $pengajuan = Pengajuan::create([
                'mahasiswa_id' => $mahasiswaId,
                'jenis' => 'mandiri',
                'judul_mandiri' => $request->judul_mandiri,
                'deskripsi_mandiri' => $request->deskripsi_mandiri,
                'prioritas' => $request->prioritas,
                'status' => 'pending'
            ]);

            // Notifikasi ke semua dosen
            $dosenIds = \App\Models\User::where('role', 'dosen')->pluck('id');
            foreach ($dosenIds as $dosenId) {
                DB::table('aktivitas')->insert([
                    'user_id' => $dosenId,
                    'tipe' => 'pengajuan_baru',
                    'pesan' => $mahasiswaName . ' mengajukan judul mandiri: ' . $request->judul_mandiri,
                    'is_read' => DB::raw('false'),
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }

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
