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

        $judul = Judul::where('status_judul', 'ditawarkan')
            ->whereRaw('is_locked = false')
            ->with([
                'laboratorium',
                'dosen',
                'pengajuan' => function ($q) {
                    $q->where('status', 'disetujui')
                        ->with('mahasiswa');
                }
            ])
            ->withCount([
                'pengajuanPilihan1',
                'pengajuanPilihan2',
                'pengajuanPilihan3'
            ])
            ->get()
            ->map(function ($item) {
                $item->peminat = $item->pengajuan_pilihan1_count
                    + $item->pengajuan_pilihan2_count
                    + $item->pengajuan_pilihan3_count;
                return $item;
            });

        $jumlahPengajuan = Pengajuan::where('mahasiswa_id', $mahasiswaId)
            ->whereIn('status', ['pending', 'disetujui'])
            ->count();

        $pengajuanSaya = Pengajuan::where('mahasiswa_id', $mahasiswaId)
            ->pluck('judul_id')
            ->toArray();

        $laboratorium = Laboratorium::all();

        $mySubmissions = Pengajuan::where('mahasiswa_id', $mahasiswaId)
            ->with([
                'pilihan1.dosen',
                'pilihan1.laboratorium',
                'pilihan2.dosen',
                'pilihan2.laboratorium',
                'pilihan3.dosen',
                'pilihan3.laboratorium'
            ])
            ->latest()
            ->get();

        // ✅ Mapping untuk Alpine.js — dilakukan di PHP supaya @json() aman
        $judulJson = $judul->map(function ($j) {
            return [
                'id' => $j->id,
                'kode' => $j->kode ?? '-',
                'nama_judul' => $j->nama_judul,
                'deskripsi' => $j->deskripsi ?? '',
                'lab_id' => $j->laboratorium_id,
                'lab_name' => $j->laboratorium->nama ?? '-',
                'dosen_name' => $j->dosen->name ?? '-',
                'peminat' => $j->peminat ?? 0,
            ];
        })->values();

        return view('mahasiswa.pengajuan', compact(
            'judul',
            'jumlahPengajuan',
            'pengajuanSaya',
            'laboratorium',
            'mySubmissions',
            'judulJson'  // ✅ tambah ini
        ))->with('title', 'Pengajuan Judul');
    }


    public function store(Request $request)
    {
        $mahasiswaId = Auth::id();
        $mahasiswaName = Auth::user()->name;

        // Cek apakah sudah pernah mengajukan
        $jumlahAktif = Pengajuan::where('mahasiswa_id', $mahasiswaId)
            ->whereIn('status', ['pending', 'disetujui'])
            ->count();

        if ($jumlahAktif > 0) {
            return back()->with('error', 'Anda sudah mengajukan judul sebelumnya.');
        }

        // Validasi input
        $validated = $request->validate([
            'judul_mandiri' => 'nullable|string|max:255',
            'deskripsi_mandiri' => 'nullable|string|max:1000',
            'pilihan_1_id' => 'required|exists:judul,id',
            'pilihan_2_id' => 'required|exists:judul,id|different:pilihan_1_id',
            'pilihan_3_id' => 'required|exists:judul,id|different:pilihan_1_id,pilihan_2_id',
            'alasan_1' => 'nullable|string|max:500',
            'alasan_2' => 'nullable|string|max:500',
            'alasan_3' => 'nullable|string|max:500',
        ], [
            'pilihan_1_id.required' => 'Pilihan 1 wajib diisi',
            'pilihan_2_id.required' => 'Pilihan 2 wajib diisi',
            'pilihan_2_id.different' => 'Pilihan 2 harus berbeda dengan Pilihan 1',
            'pilihan_3_id.required' => 'Pilihan 3 wajib diisi',
            'pilihan_3_id.different' => 'Pilihan 3 harus berbeda dengan Pilihan 1 dan 2',
        ]);

        // Ambil periode aktif
        $periodeAktif = \App\Models\Periode::periodeAktif();

        if (!$periodeAktif) {
            return back()->with('error', 'Tidak ada periode aktif saat ini. Silakan hubungi koordinator.');
        }

        // Simpan pengajuan
        $pengajuan = Pengajuan::create([
            'mahasiswa_id' => $mahasiswaId,
            'periode_id' => $periodeAktif->id,
            'judul_mandiri' => $validated['judul_mandiri'] ?? null,
            'deskripsi_mandiri' => $validated['deskripsi_mandiri'] ?? null,
            'pilihan_1_id' => $validated['pilihan_1_id'],
            'pilihan_2_id' => $validated['pilihan_2_id'],
            'pilihan_3_id' => $validated['pilihan_3_id'],
            'alasan_1' => $validated['alasan_1'] ?? null,
            'alasan_2' => $validated['alasan_2'] ?? null,
            'alasan_3' => $validated['alasan_3'] ?? null,
            'jenis' => !empty($validated['judul_mandiri']) ? 'mandiri' : 'pilih',
            'status' => 'pending',
        ]);

        // Notifikasi ke dosen (pilihan 1, 2, 3)
        $dosenIds = [];

        if ($validated['pilihan_1_id']) {
            $judul1 = Judul::find($validated['pilihan_1_id']);
            if ($judul1 && $judul1->dosen_id) {
                $dosenIds[] = $judul1->dosen_id;
            }
        }

        if ($validated['pilihan_2_id']) {
            $judul2 = Judul::find($validated['pilihan_2_id']);
            if ($judul2 && $judul2->dosen_id && !in_array($judul2->dosen_id, $dosenIds)) {
                $dosenIds[] = $judul2->dosen_id;
            }
        }

        if ($validated['pilihan_3_id']) {
            $judul3 = Judul::find($validated['pilihan_3_id']);
            if ($judul3 && $judul3->dosen_id && !in_array($judul3->dosen_id, $dosenIds)) {
                $dosenIds[] = $judul3->dosen_id;
            }
        }

        // Kirim notifikasi ke dosen yang terlibat
        foreach ($dosenIds as $dosenId) {
            DB::table('aktivitas')->insert([
                'user_id' => $dosenId,
                'tipe' => 'pengajuan_baru',
                'pesan' => $mahasiswaName . ' mengajukan judul TA dengan salah satu judul Anda sebagai pilihan',
                'is_read' => DB::raw('false'),
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        // Jika ada usulan mandiri, notifikasi ke semua dosen
        if (!empty($validated['judul_mandiri'])) {
            $allDosenIds = \App\Models\User::where('role', 'dosen')->pluck('id');
            foreach ($allDosenIds as $dosenId) {
                if (!in_array($dosenId, $dosenIds)) {
                    DB::table('aktivitas')->insert([
                        'user_id' => $dosenId,
                        'tipe' => 'pengajuan_baru',
                        'pesan' => $mahasiswaName . ' mengajukan usulan judul mandiri: ' . $validated['judul_mandiri'],
                        'is_read' => DB::raw('false'),
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                }
            }
        }

        return redirect()->route('mahasiswa.pengajuan')
            ->with('success', 'Pengajuan judul berhasil dikirim! Menunggu review dari Ka Lab.');
    }

    public function riwayat()
    {
        $mahasiswaId = Auth::id();

        $pengajuan = Pengajuan::with([
            'pilihan1.laboratorium',
            'pilihan2.laboratorium',
            'pilihan3.laboratorium',
        ])
            ->where('mahasiswa_id', $mahasiswaId)
            ->latest()
            ->get();

        // ✅ Mapping di PHP supaya @json() aman di view
        $pengajuanJson = $pengajuan->map(function ($p) {
            return [
                'id' => $p->id,
                'judul' => $p->jenis === 'pilih'
                    ? ($p->pilihan1->nama_judul ?? $p->pilihan2->nama_judul ?? $p->pilihan3->nama_judul ?? '-')
                    : ($p->judul_mandiri ?? '-'),
                'jenis' => $p->jenis,
                'status' => $p->status,
                'prioritas' => $p->prioritas ?? 1,
                'kode' => $p->jenis === 'pilih' ? ($p->pilihan1->kode ?? '') : '',
                'deskripsi' => $p->jenis === 'mandiri' ? ($p->deskripsi_mandiri ?? '') : '',
                'lab' => $p->jenis === 'pilih' && $p->pilihan1
                    ? ($p->pilihan1->laboratorium->nama ?? '')
                    : '',
                'alasan' => $p->alasan_1 ?? $p->alasan ?? '',
                'catatan_dosen' => $p->catatan_dosen ?? '',
                'waktu' => $p->created_at->diffForHumans(),
                'tanggal' => $p->created_at->format('d M Y H:i'),
                'timestamp' => $p->created_at->timestamp,
            ];
        })->values();

        return view('mahasiswa.riwayat', [
            'pengajuan' => $pengajuan,
            'pengajuanJson' => $pengajuanJson,
            'title' => 'Riwayat Pengajuan',
        ]);
    }

}
