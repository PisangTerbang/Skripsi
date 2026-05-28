<?php

namespace App\Http\Controllers\Mahasiswa;

use App\Http\Controllers\Controller;
use App\Models\Judul;
use App\Models\Pengajuan;
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
                    $q->where('status', 'disetujui')->with('mahasiswa');
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
            'judulJson'
        ))->with('title', 'Pengajuan Judul');
    }

    public function store(Request $request)
    {
        $mahasiswaId = Auth::id();
        $mahasiswaName = Auth::user()->name;

        $jumlahAktif = Pengajuan::where('mahasiswa_id', $mahasiswaId)
            ->whereIn('status', ['pending', 'disetujui'])
            ->count();

        if ($jumlahAktif > 0) {
            return back()->with('error', 'Anda sudah mengajukan judul sebelumnya.');
        }

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

        $periodeAktif = \App\Models\Periode::periodeAktif();

        if (!$periodeAktif) {
            return back()->with('error', 'Tidak ada periode aktif saat ini. Silakan hubungi koordinator.');
        }

        Pengajuan::create([
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

        $dosenIds = [];

        foreach (['pilihan_1_id', 'pilihan_2_id', 'pilihan_3_id'] as $key) {
            $j = Judul::find($validated[$key]);
            if ($j && $j->dosen_id && !in_array($j->dosen_id, $dosenIds)) {
                $dosenIds[] = $j->dosen_id;
            }
        }

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
            'pilihan1.dosen',
            'pilihan2.laboratorium',
            'pilihan2.dosen',
            'pilihan3.laboratorium',
            'pilihan3.dosen',
            'judulDitetapkan',
            'reviewerKalab',
            'reviewerKaprodi',
        ])
            ->where('mahasiswa_id', $mahasiswaId)
            ->latest()
            ->get();

        $sudahDiumumkan = DB::table('aktivitas')
            ->where('user_id', $mahasiswaId)
            ->whereIn('tipe', ['pengumuman_disetujui', 'pengumuman_ditolak'])
            ->exists();

        $pengajuanJson = $pengajuan->map(function ($p) use ($sudahDiumumkan) {
            return [
                'id' => $p->id,
                'judul' => $p->jenis === 'pilih'
                    ? ($p->pilihan1->nama_judul ?? '-')
                    : ($p->judul_mandiri ?? '-'),
                'jenis' => $p->jenis,
                'status' => $p->status,
                'prioritas' => $p->prioritas ?? 1,
                'kode' => $p->jenis === 'pilih' ? ($p->pilihan1->kode ?? '') : '',
                'deskripsi' => $p->jenis === 'mandiri' ? ($p->deskripsi_mandiri ?? '') : '',
                'lab' => $p->jenis === 'pilih' && $p->pilihan1
                    ? ($p->pilihan1->laboratorium->nama ?? '') : '',
                'alasan' => $p->alasan_1 ?? '',

                // ✅ Semua pilihan judul
                'pilihan1' => $p->pilihan1 ? [
                    'judul' => $p->pilihan1->nama_judul ?? '-',
                    'kode' => $p->pilihan1->kode ?? '-',
                    'dosen' => $p->pilihan1->dosen->name ?? '-',
                    'lab' => $p->pilihan1->laboratorium->nama ?? '-',
                    'alasan' => $p->alasan_1 ?? '',
                ] : null,
                'pilihan2' => $p->pilihan2 ? [
                    'judul' => $p->pilihan2->nama_judul ?? '-',
                    'kode' => $p->pilihan2->kode ?? '-',
                    'dosen' => $p->pilihan2->dosen->name ?? '-',
                    'lab' => $p->pilihan2->laboratorium->nama ?? '-',
                    'alasan' => $p->alasan_2 ?? '',
                ] : null,
                'pilihan3' => $p->pilihan3 ? [
                    'judul' => $p->pilihan3->nama_judul ?? '-',
                    'kode' => $p->pilihan3->kode ?? '-',
                    'dosen' => $p->pilihan3->dosen->name ?? '-',
                    'lab' => $p->pilihan3->laboratorium->nama ?? '-',
                    'alasan' => $p->alasan_3 ?? '',
                ] : null,
                'judul_mandiri' => $p->judul_mandiri ?? null,
                'deskripsi_mandiri' => $p->deskripsi_mandiri ?? null,

                'catatan_dosen' => $p->catatan_dosen ?? '',
                'waktu' => $p->created_at->diffForHumans(),
                'tanggal' => $p->created_at->format('d M Y H:i'),
                'timestamp' => $p->created_at->timestamp,
                'status_kalab' => $p->status_kalab,
                'status_kaprodi' => $p->status_kaprodi,
                'catatan_kalab' => $p->catatan_kalab_pengajuan ?? '',
                'catatan_kaprodi' => $p->catatan_kaprodi ?? '',
                'judul_ditetapkan' => $p->judulDitetapkan
                    ? ($p->judulDitetapkan->nama_judul ?? $p->judulDitetapkan->judul ?? '-')
                    : null,
                'reviewer_kalab' => $p->reviewerKalab->name ?? null,
                'reviewer_kaprodi' => $p->reviewerKaprodi->name ?? null,
                'sudah_diumumkan' => $sudahDiumumkan,
            ];
        })->values();

        return view('mahasiswa.riwayat', [
            'pengajuan' => $pengajuan,
            'pengajuanJson' => $pengajuanJson,
            'title' => 'Riwayat Pengajuan',
        ]);
    }
}
