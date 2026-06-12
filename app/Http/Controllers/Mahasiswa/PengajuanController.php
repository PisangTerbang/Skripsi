<?php

namespace App\Http\Controllers\Mahasiswa;

use App\Http\Controllers\Controller;
use App\Models\Judul;
use App\Models\Pengajuan;
use App\Models\Laboratorium;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class PengajuanController extends Controller
{
    public function index()
    {
        $mahasiswaId = Auth::id();

        $periodeAktif = \App\Models\Periode::periodeAktif();
        // Peminat dihitung hanya untuk periode aktif → ikut reset saat ganti periode.
        $activePeriodeId = $periodeAktif?->id;
        $diPeriodeAktif = fn($q) => $q->where('periode_id', $activePeriodeId);

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
                'pengajuanPilihan1' => $diPeriodeAktif,
                'pengajuanPilihan2' => $diPeriodeAktif,
                'pengajuanPilihan3' => $diPeriodeAktif,
            ])
            ->get()
            ->map(function ($item) {
                $item->peminat = $item->pengajuan_pilihan1_count
                    + $item->pengajuan_pilihan2_count
                    + $item->pengajuan_pilihan3_count;
                return $item;
            });

        // Gate per-periode: 1 pengajuan per periode apa pun statusnya (hasil dirahasiakan s/d pengumuman).
        // Pengajuan periode lama (arsip) tidak menghalangi pengajuan di periode baru.
        $jumlahPengajuan = $periodeAktif
            ? Pengajuan::where('mahasiswa_id', $mahasiswaId)
                ->where('periode_id', $periodeAktif->id)
                ->count()
            : 0;

        // Periode yang pengumumannya sudah dikirim — hasil baru boleh dibuka ke mahasiswa.
        $announcedPeriodes = DB::table('pengumuman')
            ->whereNotNull('dikirim_at')
            ->pluck('periode_id')
            ->all();

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

        // ✅ Daftar dosen untuk dropdown judul mandiri
        $dosenList = User::where('role', 'dosen')
            ->orderBy('name')
            ->get();

        return view('mahasiswa.pengajuan', compact(
            'judul',
            'jumlahPengajuan',
            'pengajuanSaya',
            'laboratorium',
            'mySubmissions',
            'judulJson',
            'dosenList', // ✅ tambah ini
            'periodeAktif',
            'announcedPeriodes'
        ))->with('title', 'Pengajuan Judul');
    }

    public function store(Request $request)
    {
        $mahasiswaId = Auth::id();
        $mahasiswaName = Auth::user()->name;

        // Gate periode: semua aktivitas pengajuan hanya berjalan saat ada periode aktif (dikontrol Koordinator TA).
        $periodeAktif = \App\Models\Periode::periodeAktif();

        if (!$periodeAktif) {
            return back()->with('error', 'Belum ada periode aktif. Pengajuan TA dibuka oleh Koordinator TA.');
        }

        // Gate per-periode: 1 pengajuan per periode apa pun statusnya — hasil dirahasiakan s/d pengumuman,
        // jadi tidak ada re-submit di periode yang sama. Periode baru (arsip) = bisa mengajukan kembali.
        $jumlahAktif = Pengajuan::where('mahasiswa_id', $mahasiswaId)
            ->where('periode_id', $periodeAktif->id)
            ->count();

        if ($jumlahAktif > 0) {
            return back()->with('error', 'Anda sudah mengajukan judul pada periode ini. Hasil akan diumumkan oleh Koordinator TA.');
        }

        $validated = $request->validate([
            'judul_mandiri' => 'nullable|string|max:255',
            'deskripsi_mandiri' => 'nullable|string|max:1000',
            // ✅ Wajib pilih dosen kalau ada judul mandiri
            'dosen_pembimbing_id' => 'required_with:judul_mandiri|nullable|exists:users,id',
            'pilihan_1_id' => 'required|exists:judul,id',
            'pilihan_2_id' => 'required|exists:judul,id|different:pilihan_1_id',
            'pilihan_3_id' => 'required|exists:judul,id|different:pilihan_1_id,pilihan_2_id',
            'alasan_1' => 'nullable|string|max:500',
            'alasan_2' => 'nullable|string|max:500',
            'alasan_3' => 'nullable|string|max:500',
        ], [
            'dosen_pembimbing_id.required_with' => 'Dosen pembimbing wajib dipilih jika mengajukan judul mandiri.',
            'pilihan_1_id.required' => 'Pilihan 1 wajib diisi',
            'pilihan_2_id.required' => 'Pilihan 2 wajib diisi',
            'pilihan_2_id.different' => 'Pilihan 2 harus berbeda dengan Pilihan 1',
            'pilihan_3_id.required' => 'Pilihan 3 wajib diisi',
            'pilihan_3_id.different' => 'Pilihan 3 harus berbeda dengan Pilihan 1 dan 2',
        ]);

        Pengajuan::create([
            'mahasiswa_id' => $mahasiswaId,
            'periode_id' => $periodeAktif->id,
            'judul_mandiri' => $validated['judul_mandiri'] ?? null,
            'deskripsi_mandiri' => $validated['deskripsi_mandiri'] ?? null,
            'dosen_pembimbing_id' => $validated['dosen_pembimbing_id'] ?? null, // ✅
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

        // ✅ Notifikasi ke dosen pembimbing yang dipilih untuk judul mandiri
        if (!empty($validated['judul_mandiri']) && !empty($validated['dosen_pembimbing_id'])) {
            $dosenPembimbingId = $validated['dosen_pembimbing_id'];
            if (!in_array($dosenPembimbingId, $dosenIds)) {
                $dosenIds[] = $dosenPembimbingId;
            }
        }

        $now = now();
        $rows = [];

        // Dosen pemilik judul yang dipilih (termasuk pembimbing judul mandiri)
        $dosenLink = route('dosen.pengajuan', [], false);
        foreach ($dosenIds as $dosenId) {
            $rows[] = [
                'user_id' => $dosenId,
                'tipe' => 'pengajuan_baru',
                'pesan' => $mahasiswaName . ' memilih salah satu judul Anda pada pengajuan TA-nya.',
                'link' => $dosenLink,
                'is_read' => DB::raw('false'),
                'created_at' => $now,
                'updated_at' => $now,
            ];
        }

        // ✅ Notifikasi ke semua Ka Lab: pengajuan baru masuk untuk direview
        $kalabLink = route('ka-lab.pengajuan.index', [], false);
        foreach (User::where('role', 'ka_lab')->pluck('id') as $kalabId) {
            $rows[] = [
                'user_id' => $kalabId,
                'tipe' => 'pengajuan_masuk',
                'pesan' => $mahasiswaName . ' mengirim pengajuan judul TA baru untuk direview.',
                'link' => $kalabLink,
                'is_read' => DB::raw('false'),
                'created_at' => $now,
                'updated_at' => $now,
            ];
        }

        // ✅ Satu kali batch insert, bukan N query
        if (!empty($rows)) {
            DB::table('aktivitas')->insert($rows);
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
            'periode',
        ])
            ->where('mahasiswa_id', $mahasiswaId)
            ->latest()
            ->get();

        // Periode yang pengumumannya sudah dikirim (hasil baru boleh ditampilkan)
        $announcedPeriodes = DB::table('pengumuman')
            ->whereNotNull('dikirim_at')
            ->pluck('periode_id')
            ->all();

        $pengajuanJson = $pengajuan->map(function ($p) use ($announcedPeriodes) {
            // Hasil (keputusan Ka Lab/Prodi & judul ditetapkan) dirahasiakan sampai pengumuman resmi.
            $announced = in_array($p->periode_id, $announcedPeriodes);

            // Judul yang DITERIMA — tangani dua sumber:
            //  1) judul katalog (judul_ditetapkan_id terisi) → cocokkan ke pilihan ke-berapa.
            //  2) usulan mandiri (judul_ditetapkan_id null tapi jenis mandiri & disetujui).
            $judulDiterima = null;
            $sumberDiterima = null;
            if ($announced && $p->status === 'disetujui') {
                if ($p->judul_ditetapkan_id) {
                    $judulDiterima = $p->judulDitetapkan->nama_judul ?? $p->judulDitetapkan->judul ?? '-';
                    $sumberDiterima = match (true) {
                        $p->judul_ditetapkan_id == $p->pilihan_1_id => 'pilihan_1',
                        $p->judul_ditetapkan_id == $p->pilihan_2_id => 'pilihan_2',
                        $p->judul_ditetapkan_id == $p->pilihan_3_id => 'pilihan_3',
                        default => 'lain',
                    };
                } elseif ($p->jenis === 'mandiri' && $p->judul_mandiri) {
                    $judulDiterima = $p->judul_mandiri;
                    $sumberDiterima = 'mandiri';
                }
            }
            $sumberLabel = match ($sumberDiterima) {
                'mandiri' => 'Usulan Mandiri',
                'pilihan_1' => 'Pilihan ke-1',
                'pilihan_2' => 'Pilihan ke-2',
                'pilihan_3' => 'Pilihan ke-3',
                'lain' => 'Judul lain',
                default => null,
            };

            return [
                'id' => $p->id,
                'periode' => $p->periode
                    ? ($p->periode->nama ?? trim(($p->periode->semester ?? '') . ' ' . ($p->periode->tahun_ajaran ?? '')))
                    : '-',
                'judul' => $p->jenis === 'pilih'
                    ? ($p->pilihan1->nama_judul ?? '-')
                    : ($p->judul_mandiri ?? '-'),
                'jenis' => $p->jenis,
                'status' => $announced ? $p->status : 'pending',
                'prioritas' => $p->prioritas ?? 1,
                'kode' => $p->jenis === 'pilih' ? ($p->pilihan1->kode ?? '') : '',
                'deskripsi' => $p->jenis === 'mandiri' ? ($p->deskripsi_mandiri ?? '') : '',
                'lab' => $p->jenis === 'pilih' && $p->pilihan1
                    ? ($p->pilihan1->laboratorium->nama ?? '') : '',
                'alasan' => $p->alasan_1 ?? '',
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
                'status_kalab' => $announced ? $p->status_kalab : null,
                'status_kaprodi' => $announced ? $p->status_kaprodi : null,
                'catatan_kalab' => $announced ? ($p->catatan_kalab_pengajuan ?? '') : '',
                'catatan_kaprodi' => $announced ? ($p->catatan_kaprodi ?? '') : '',
                'judul_ditetapkan' => $announced && $p->judulDitetapkan
                    ? ($p->judulDitetapkan->nama_judul ?? $p->judulDitetapkan->judul ?? '-')
                    : null,
                // Judul diterima (mencakup usulan mandiri) + dari sumber/pilihan mana.
                'judul_diterima' => $judulDiterima,
                'judul_diterima_sumber' => $sumberDiterima,
                'judul_diterima_sumber_label' => $sumberLabel,
                'reviewer_kalab' => $announced ? ($p->reviewerKalab->name ?? null) : null,
                'reviewer_kaprodi' => $announced ? ($p->reviewerKaprodi->name ?? null) : null,
                'sudah_diumumkan' => $announced,
            ];
        })->values();

        return view('mahasiswa.riwayat', [
            'pengajuan' => $pengajuan,
            'pengajuanJson' => $pengajuanJson,
            'announcedPeriodes' => $announcedPeriodes,
            'title' => 'Riwayat Pengajuan',
        ]);
    }
}
