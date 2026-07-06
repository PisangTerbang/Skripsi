<?php

namespace App\Http\Controllers\KaLab;

use App\Http\Controllers\Controller;
use App\Models\Pengajuan;
use App\Models\Judul;
use App\Models\Laboratorium;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class PengajuanController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();

        if ($user->role !== 'ka_lab') {
            abort(403, 'Anda tidak memiliki akses sebagai Kepala Lab');
        }

        $status = $request->get('status', 'all');
        $search = $request->get('search', '');

        // Ka Lab hanya menangani laboratoriumnya sendiri (review berjenjang: hanya
        // pengajuan yang lab_aktif_id-nya = lab ini yang muncul di antrean).
        $myLab = $user->laboratorium_id;

        // Filter periode: default PERIODE AKTIF (tak campur), tapi bisa memilih periode
        // lain / "semua" untuk menelusuri riwayat — lebih terstruktur.
        $periodeList = \App\Models\Periode::urutKronologis()->get();
        $aktifId = \App\Models\Periode::periodeAktif()?->id;
        $selectedPeriode = $request->get('periode_id') ?? ($aktifId ? (string) $aktifId : 'semua');
        $scopePeriode = fn($q) => $q->where('lab_aktif_id', $myLab)
            ->when($selectedPeriode !== 'semua', fn($qq) => $qq->where('periode_id', $selectedPeriode));

        $query = $scopePeriode(Pengajuan::with([
            'mahasiswa',
            'periode',
            'pilihan1.laboratorium',
            'pilihan1.dosen',
            'pilihan2.laboratorium',
            'pilihan2.dosen',
            'pilihan3.laboratorium',
            'pilihan3.dosen',
            'judulDitetapkan.dosen',
            'reviewerKalab',
        ]));

        if ($status === 'pending') {
            $query->where(function ($q) {
                $q->where('status_kalab', 'pending')->orWhereNull('status_kalab');
            });
        } elseif ($status === 'disetujui') {
            $query->where('status_kalab', 'disetujui');
        } elseif ($status === 'ditolak') {
            $query->where('status_kalab', 'ditolak');
        }

        if ($search) {
            $query->whereHas('mahasiswa', function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('nim', 'like', "%{$search}%");
            });
        }

        // Urutan konsisten: terbaru di atas (deterministik via id).
        $pengajuan = $query->orderByDesc('id')->paginate(10)->withQueryString();

        $statScope = fn() => $scopePeriode(Pengajuan::query());
        $stats = [
            'total' => $statScope()->count(),
            'pending' => $statScope()->where(function ($q) {
                $q->where('status_kalab', 'pending')->orWhereNull('status_kalab');
            })->count(),
            'disetujui' => $statScope()->where('status_kalab', 'disetujui')->count(),
            'ditolak' => $statScope()->where('status_kalab', 'ditolak')->count(),
        ];

        return view('ka_lab.pengajuan.index', compact('pengajuan', 'stats', 'status', 'search', 'periodeList', 'selectedPeriode', 'aktifId'));
    }

    public function show($id)
    {
        $user = Auth::user();

        if ($user->role !== 'ka_lab') {
            abort(403, 'Anda tidak memiliki akses sebagai Kepala Lab');
        }

        $pengajuan = Pengajuan::with([
            'mahasiswa',
            'periode',
            'pilihan1.dosen',
            'pilihan1.laboratorium',
            'pilihan2.dosen',
            'pilihan2.laboratorium',
            'pilihan3.dosen',
            'pilihan3.laboratorium',
            'judulDitetapkan.dosen',
            'reviewerKalab',
            'reviewerKaprodi',
            'labAktif',
        ])->findOrFail($id);

        // Guard: hanya bila pengajuan sedang ditangani lab Ka Lab ini (kecuali sudah final,
        // biarkan dilihat sebagai riwayat lab yang pernah menanganinya).
        if ($pengajuan->lab_aktif_id !== $user->laboratorium_id) {
            abort(403, 'Pengajuan ini bukan wewenang laboratorium Anda saat ini.');
        }

        // Prioritas & judul yang sedang direview lab ini (untuk aksi setuju/tolak).
        $prioritasAktif = $pengajuan->prioritas_aktif ?: 1;
        $judulAktif = $pengajuan->jenis === 'mandiri'
            ? null
            : $pengajuan->judulPrioritasAktif();

        $pilihanStatus = [];

        foreach ([
            'pilihan_1' => $pengajuan->pilihan_1_id,
            'pilihan_2' => $pengajuan->pilihan_2_id,
            'pilihan_3' => $pengajuan->pilihan_3_id,
        ] as $key => $judulId) {
            if (!$judulId) {
                $pilihanStatus[$key] = null;
                continue;
            }

            // Hanya cek tabrakan DALAM periode yang sama. Judul yang dipakai di
            // periode lampau otomatis terbuka lagi di periode ini (tidak "nyangkut").
            $sudahDiambil = Pengajuan::with('mahasiswa')
                ->where('judul_ditetapkan_id', $judulId)
                ->where('periode_id', $pengajuan->periode_id)
                ->where('status_kalab', 'disetujui')
                ->where('id', '!=', $pengajuan->id)
                ->first();

            $pilihanStatus[$key] = $sudahDiambil ? [
                'diambil' => true,
                'nama' => $sudahDiambil->mahasiswa->name ?? '-',
                'nim' => $sudahDiambil->mahasiswa->nim ?? '-',
                'email' => $sudahDiambil->mahasiswa->email ?? '-',
            ] : ['diambil' => false];
        }

        $semuaPilihanDiambil = collect($pilihanStatus)
            ->filter(fn($s) => $s !== null)
            ->every(fn($s) => $s['diambil'] === true);

        // ✅ Daftar laboratorium untuk dropdown judul mandiri
        $laboratorium = Laboratorium::orderBy('nama')->get();

        return view('ka_lab.pengajuan.show', compact(
            'pengajuan',
            'pilihanStatus',
            'semuaPilihanDiambil',
            'laboratorium',
            'prioritasAktif',
            'judulAktif'
        ));
    }

    public function approve(Request $request, $id)
    {
        // Review berjenjang: Ka Lab menyetujui judul pada PRIORITAS AKTIF pengajuan
        // (bukan memilih bebas 1 dari 3). Catatan kini wajib.
        $request->validate([
            'catatan_kalab' => 'required|string|max:1000',
        ], [
            'catatan_kalab.required' => 'Catatan validasi wajib diisi.',
        ]);

        $pengajuan = Pengajuan::findOrFail($id);
        $user = Auth::user();

        // Guard: pengajuan harus sedang ditangani lab milik Ka Lab ini.
        if ($pengajuan->lab_aktif_id !== $user->laboratorium_id) {
            return back()->with('error', 'Pengajuan ini bukan wewenang laboratorium Anda saat ini.');
        }

        if (!$pengajuan->canBeReviewedByKalab()) {
            return back()->with('error', 'Pengajuan ini tidak dapat direview saat ini.');
        }

        if (!$pengajuan->isPeriodeAktif()) {
            return back()->with('error', 'Pengajuan ini berada di periode yang sudah ditutup (arsip) dan tidak dapat diproses.');
        }

        // Tentukan judul & sumber dari prioritas aktif (atau mandiri).
        if ($pengajuan->jenis === 'mandiri') {
            $sumberJudul = 'mandiri';
            $judulId = null;
        } else {
            $prioritas = $pengajuan->prioritas_aktif ?: 1;
            $sumberJudul = 'pilihan_' . $prioritas;
            $judulId = $pengajuan->{"pilihan_{$prioritas}_id"};

            // Cek apakah judul prioritas ini sudah diambil mahasiswa lain (periode sama).
            if ($judulId) {
                $sudahDiambil = Pengajuan::where('judul_ditetapkan_id', $judulId)
                    ->where('periode_id', $pengajuan->periode_id)
                    ->where('status_kalab', 'disetujui')
                    ->where('id', '!=', $pengajuan->id)
                    ->exists();

                if ($sudahDiambil) {
                    return back()->with('error', 'Judul ini sudah diambil mahasiswa lain. Silakan tolak agar diteruskan ke prioritas berikutnya.');
                }
            }
        }

        DB::beginTransaction();
        try {
            if ($sumberJudul === 'mandiri') {
                // Lab sudah ditentukan dosen saat konfirmasi (lab_aktif_id).
                $judulId = $this->createJudulMandiri($pengajuan, $pengajuan->lab_aktif_id);
            }

            $success = $pengajuan->approveByKalab(
                userId: $user->id,
                judulId: $judulId,
                sumberJudul: $sumberJudul,
                catatan: $request->catatan_kalab
            );

            if (!$success) {
                throw new \Exception('Gagal menyetujui pengajuan.');
            }

            DB::commit();

            return redirect()
                ->route('ka-lab.pengajuan.index')
                ->with('success', 'Pengajuan berhasil disetujui! Mahasiswa akan mendapat notifikasi setelah pengumuman resmi dari Koordinator TA.');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function reject(Request $request, $id)
    {
        $request->validate([
            'catatan_kalab' => 'required|string|max:1000',
        ], [
            'catatan_kalab.required' => 'Catatan penolakan wajib diisi.',
        ]);

        $pengajuan = Pengajuan::findOrFail($id);
        $user = Auth::user();

        // Guard: pengajuan harus sedang ditangani lab milik Ka Lab ini.
        if ($pengajuan->lab_aktif_id !== $user->laboratorium_id) {
            return back()->with('error', 'Pengajuan ini bukan wewenang laboratorium Anda saat ini.');
        }

        if (!$pengajuan->canBeReviewedByKalab()) {
            return back()->with('error', 'Pengajuan ini tidak dapat direview saat ini.');
        }

        if (!$pengajuan->isPeriodeAktif()) {
            return back()->with('error', 'Pengajuan ini berada di periode yang sudah ditutup (arsip) dan tidak dapat diproses.');
        }

        DB::beginTransaction();
        try {
            $success = $pengajuan->rejectByKalab(
                userId: $user->id,
                catatan: $request->catatan_kalab
            );

            if (!$success) {
                throw new \Exception('Gagal menolak pengajuan.');
            }

            // Pesan menyesuaikan: diteruskan ke prioritas berikutnya vs ditolak final.
            $pengajuan->refresh();
            $pesan = is_null($pengajuan->status_kalab)
                ? 'Prioritas ini ditolak. Pengajuan diteruskan ke lab prioritas berikutnya (prioritas ke-' . $pengajuan->prioritas_aktif . ').'
                : 'Pengajuan ditolak final (semua prioritas habis). Mahasiswa diberi tahu setelah pengumuman resmi.';

            DB::commit();

            return redirect()
                ->route('ka-lab.pengajuan.index')
                ->with('success', $pesan);

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    // ✅ Terima laboratorium_id sebagai parameter
    private function createJudulMandiri($pengajuan, $laboratoriumId = null)
    {
        // Judul mandiri DISIMPAN sebagai golongan 'mandiri' — hanya untuk pencatatan
        // & laporan, TIDAK dijadikan katalog 'ditawarkan'. Jadi ia tidak akan muncul
        // sebagai pilihan judul di periode berikutnya (daftar mahasiswa memfilter
        // status_judul = 'ditawarkan').
        $judul = Judul::create([
            'nama_judul' => $pengajuan->judul_mandiri,
            'deskripsi' => $pengajuan->deskripsi_mandiri,
            // Dosen pembimbing yang dipilih mahasiswa untuk judul mandiri
            'dosen_id' => $pengajuan->dosen_pembimbing_id,
            'laboratorium_id' => $laboratoriumId,
            'kode' => Judul::generateKode($laboratoriumId),
            'status_judul' => 'mandiri',
            'aktif' => DB::raw('false'),
            'is_locked' => DB::raw('false'),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('judul_logs')->insert([
            'judul_id' => $judul->id,
            'user_id' => Auth::id(),
            'aksi' => 'dibuat_dari_pengajuan',
            'dari_status' => null,
            'ke_status' => 'mandiri',
            'catatan' => 'Judul mandiri dari pengajuan mahasiswa: ' . $pengajuan->mahasiswa->name
                . ' (disimpan sebagai golongan mandiri, tidak ditawarkan).',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return $judul->id;
    }
}
