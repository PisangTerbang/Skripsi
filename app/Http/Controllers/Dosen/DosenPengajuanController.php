<?php

namespace App\Http\Controllers\Dosen;

use App\Http\Controllers\Controller;
use App\Models\Pengajuan;
use App\Models\Periode;
use App\Models\Laboratorium;
use App\Models\User;
use App\Models\Aktivitas;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DosenPengajuanController extends Controller
{
    /**
     * Halaman VIEW-ONLY: dosen memantau pengajuan yang melibatkan judulnya.
     * Keputusan (setuju/tolak) ada di Ka Lab -> Prodi, BUKAN dosen.
     *
     * Sesuai prinsip "semua aktivitas dinaungi 1 periode aktif": default tampilan
     * di-scope ke periode aktif, tapi dosen bisa memilih periode lain (atau "semua")
     * lewat filter untuk menelusuri data lama (riwayat).
     */
    public function index(Request $request)
    {
        $user = Auth::user();
        $dosenId = $user->id;

        // Daftar periode untuk dropdown filter + periode aktif sebagai default.
        $periodeList = Periode::urutKronologis()->get();
        $aktifId = Periode::periodeAktif()?->id;
        // Default ke periode aktif; jika belum ada periode aktif, tampilkan semua (riwayat).
        $selectedPeriode = $request->get('periode_id') ?? ($aktifId ? (string) $aktifId : 'semua');

        // Pengajuan yang melibatkan dosen ini:
        // - judul mandiri yang menunjuk dosen ini sebagai pembimbing, ATAU
        // - memilih salah satu judul milik dosen ini sebagai pilihan 1/2/3.
        $pengajuan = Pengajuan::with([
            'mahasiswa',
            'periode',
            'labAktif',
            'judulDitetapkan.laboratorium',
            'pilihan1.laboratorium',
            'pilihan1.dosen',
            'pilihan2.laboratorium',
            'pilihan2.dosen',
            'pilihan3.laboratorium',
            'pilihan3.dosen',
        ])
            ->when($selectedPeriode !== 'semua', fn($q) => $q->where('periode_id', $selectedPeriode))
            ->where(function ($q) use ($dosenId) {
                $q->where(function ($qm) use ($dosenId) {
                    $qm->where('jenis', 'mandiri')
                        ->where('dosen_pembimbing_id', $dosenId);
                })
                    ->orWhereHas('pilihan1', fn($q2) => $q2->where('dosen_id', $dosenId))
                    ->orWhereHas('pilihan2', fn($q2) => $q2->where('dosen_id', $dosenId))
                    ->orWhereHas('pilihan3', fn($q2) => $q2->where('dosen_id', $dosenId));
            })
            ->orderBy('created_at', 'desc')
            ->get();

        // Tentukan judul milik dosen ini yang dipilih (untuk label & grouping)
        $pengajuan->each(function ($p) use ($dosenId) {
            // Default: belum settle di tempat lain.
            $p->settled_elsewhere = false;
            $p->settled_judul = null;
            $p->settled_lab = null;

            if ($p->jenis === 'mandiri') {
                $p->match_key = 'mandiri_' . $p->id;
                $p->match_nama = $p->judul_mandiri ?? '-';
                $p->match_kode = '';
                $p->match_alasan = '';
                $p->match_prioritas = null;
                return;
            }

            foreach ([1, 2, 3] as $n) {
                $j = $p->{'pilihan' . $n};
                if ($j && $j->dosen_id === $dosenId) {
                    $p->match_key = 'judul_' . $j->id;
                    $p->match_nama = $j->nama_judul ?? '-';
                    $p->match_kode = $j->kode ?? '';
                    $p->match_alasan = $p->{'alasan_' . $n} ?? '';
                    $p->match_prioritas = $n; // judul dosen ini dipilih sebagai pilihan ke-$n

                    // Peminat sudah DISETUJUI judul LAIN (bukan judul dosen ini) → beri tahu agar tak ambigu.
                    if (
                        $p->status_kalab === 'disetujui'
                        && $p->judul_ditetapkan_id
                        && (int) $p->judul_ditetapkan_id !== (int) $j->id
                    ) {
                        $p->settled_elsewhere = true;
                        $p->settled_judul = $p->judulDitetapkan->nama_judul ?? '-';
                        $p->settled_lab = $p->judulDitetapkan->laboratorium->nama ?? '-';
                    }
                    return;
                }
            }

            // Fallback (seharusnya tak terjadi karena filter di atas)
            $p->match_key = 'lain_' . $p->id;
            $p->match_nama = '-';
            $p->match_kode = '';
            $p->match_alasan = '';
            $p->match_prioritas = null;
        });

        $grouped = $pengajuan->groupBy('match_key');

        $totalPengajuan = $pengajuan->count();
        $pending = $pengajuan->where('status', 'pending')->count();
        $disetujui = $pengajuan->where('status', 'disetujui')->count();
        $ditolak = $pengajuan->where('status', 'ditolak')->count();

        $pengajuanJson = $grouped->map(function ($items) {
            $first = $items->first();

            return [
                'judul_id' => $first->match_key,
                'judul' => $first->match_nama,
                'kode' => $first->match_kode,
                'deskripsi' => $first->jenis === 'mandiri' ? ($first->deskripsi_mandiri ?? '') : '',
                'jenis' => $first->jenis,
                'is_owner' => true, // semua yang tampil melibatkan dosen ini
                'items' => $items->map(function ($p) {
                    return [
                        'id' => $p->id,
                        'mahasiswa' => $p->mahasiswa->name ?? '-',
                        'status' => $p->status,
                        'jenis' => $p->jenis,
                        'prioritas' => $p->match_prioritas,
                        'judul_text' => $p->match_nama,
                        'alasan' => $p->match_alasan,
                        'catatan_dosen' => $p->catatan_dosen ?? '',
                        'waktu' => $p->created_at->diffForHumans(),
                        'periode' => $p->periode->nama ?? trim(($p->periode->semester ?? '') . ' ' . ($p->periode->tahun_ajaran ?? '')) ?: '-',
                        'status_kaprodi' => $p->status_kaprodi ?? '',
                        // Untuk mandiri: null=menunggu konfirmasi dosen, dikonfirmasi, ditolak.
                        'status_dosen' => $p->status_dosen,
                        // Lab tujuan yang dipilih dosen saat mengonfirmasi usulan mandiri.
                        'lab_aktif' => optional($p->labAktif)->nama,
                        // Peminat ini sudah disetujui judul LAIN (di lab lain) → agar tak ambigu.
                        'settled_elsewhere' => (bool) ($p->settled_elsewhere ?? false),
                        'settled_judul' => $p->settled_judul ?? null,
                        'settled_lab' => $p->settled_lab ?? null,
                    ];
                })->values(),
            ];
        })->values();

        // Usulan mandiri PERIODE AKTIF yang menunggu konfirmasi dosen ini (pilih lab).
        $mandiriPending = Pengajuan::with(['mahasiswa', 'periode'])
            ->where('jenis', 'mandiri')
            ->where('dosen_pembimbing_id', $dosenId)
            ->whereNull('status_dosen')
            ->when($aktifId, fn($q) => $q->where('periode_id', $aktifId))
            ->orderBy('created_at', 'desc')
            ->get();

        $labList = Laboratorium::orderBy('nama')->get();

        return view('dosen.pengajuan', [
            'pengajuanJson' => $pengajuanJson,
            'totalPengajuan' => $totalPengajuan,
            'pending' => $pending,
            'disetujui' => $disetujui,
            'ditolak' => $ditolak,
            'periodeList' => $periodeList,
            'selectedPeriode' => $selectedPeriode,
            'aktifId' => $aktifId,
            'mandiriPending' => $mandiriPending,
            'labList' => $labList,
            'title' => 'Pengajuan Mahasiswa',
        ]);
    }

    /**
     * Konfirmasi usulan mandiri: dosen menentukan laboratorium, lalu pengajuan
     * diteruskan ke Ka Lab lab tersebut (masuk antrean review berjenjang).
     */
    public function konfirmasiMandiri(Request $request, $id)
    {
        $request->validate([
            'laboratorium_id' => 'required|exists:laboratorium,id',
        ], [
            'laboratorium_id.required' => 'Laboratorium wajib dipilih.',
        ]);

        $dosenId = Auth::id();
        $pengajuan = Pengajuan::where('id', $id)
            ->where('jenis', 'mandiri')
            ->where('dosen_pembimbing_id', $dosenId)
            ->firstOrFail();

        if (!is_null($pengajuan->status_dosen)) {
            return back()->with('error', 'Usulan ini sudah dikonfirmasi atau ditolak.');
        }

        $pengajuan->update([
            'status_dosen' => 'dikonfirmasi',
            'lab_aktif_id' => $request->laboratorium_id,
            'prioritas_aktif' => 1,
        ]);

        // Notifikasi Ka Lab lab terkait.
        $kaLabIds = User::where('role', 'ka_lab')
            ->where('laboratorium_id', $request->laboratorium_id)
            ->pluck('id')->all();
        if ($kaLabIds) {
            Aktivitas::buatBanyak(
                $kaLabIds,
                'pengajuan_masuk',
                'Usulan judul mandiri dikonfirmasi dosen & masuk ke lab Anda untuk direview.',
                route('ka-lab.pengajuan.index', [], false)
            );
        }

        return back()->with('success', 'Usulan mandiri dikonfirmasi & diteruskan ke Ka Lab lab terkait.');
    }

    /**
     * Dosen menolak menjadi pembimbing usulan mandiri → pengajuan ditolak final.
     */
    public function tolakMandiri(Request $request, $id)
    {
        $request->validate([
            'catatan_dosen' => 'required|string|max:1000',
        ], [
            'catatan_dosen.required' => 'Alasan penolakan wajib diisi.',
        ]);

        $dosenId = Auth::id();
        $pengajuan = Pengajuan::where('id', $id)
            ->where('jenis', 'mandiri')
            ->where('dosen_pembimbing_id', $dosenId)
            ->firstOrFail();

        if (!is_null($pengajuan->status_dosen)) {
            return back()->with('error', 'Usulan ini sudah dikonfirmasi atau ditolak.');
        }

        $pengajuan->update([
            'status_dosen' => 'ditolak',
            'status' => 'ditolak',
            'catatan_dosen' => $request->catatan_dosen,
        ]);

        return back()->with('success', 'Usulan mandiri ditolak.');
    }
}
