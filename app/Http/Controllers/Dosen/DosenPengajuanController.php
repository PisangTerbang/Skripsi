<?php

namespace App\Http\Controllers\Dosen;

use App\Http\Controllers\Controller;
use App\Models\Pengajuan;
use Illuminate\Support\Facades\Auth;

class DosenPengajuanController extends Controller
{
    /**
     * Halaman VIEW-ONLY: dosen memantau pengajuan yang melibatkan judulnya.
     * Keputusan (setuju/tolak) ada di Ka Lab -> Prodi, BUKAN dosen.
     */
    public function index()
    {
        $user = Auth::user();
        $dosenId = $user->id;

        // Pengajuan yang melibatkan dosen ini:
        // - judul mandiri yang menunjuk dosen ini sebagai pembimbing, ATAU
        // - memilih salah satu judul milik dosen ini sebagai pilihan 1/2/3.
        $pengajuan = Pengajuan::with([
            'mahasiswa',
            'pilihan1.laboratorium',
            'pilihan1.dosen',
            'pilihan2.laboratorium',
            'pilihan2.dosen',
            'pilihan3.laboratorium',
            'pilihan3.dosen',
        ])
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
                        'status_kaprodi' => $p->status_kaprodi ?? '',
                    ];
                })->values(),
            ];
        })->values();

        return view('dosen.pengajuan', [
            'pengajuanJson' => $pengajuanJson,
            'totalPengajuan' => $totalPengajuan,
            'pending' => $pending,
            'disetujui' => $disetujui,
            'ditolak' => $ditolak,
            'title' => 'Pengajuan Mahasiswa',
        ]);
    }
}
