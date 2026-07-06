<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class PengajuanSeeder extends Seeder
{
    public function run(): void
    {
        // Pembersihan tabel terpusat di DatabaseSeeder (TRUNCATE ... CASCADE).
        $mhs = DB::table('users')->where('role', 'mahasiswa')->orderBy('nim')->pluck('id')->all();
        $kalab = DB::table('users')->where('role', 'ka_lab')->value('id');
        $prodi = DB::table('users')->where('role', 'prodi')->value('id');
        $juduls = DB::table('judul')->orderBy('id')->get(['id', 'dosen_id', 'laboratorium_id'])->all();
        $jN = count($juduls);

        $aktif = DB::table('periode')->whereRaw('is_active = true')->first();
        $arsip = DB::table('periode')->whereRaw('is_active = false')
            ->orderBy('tanggal_buka')->get()->all();

        $alasan = [
            'Topik sesuai dengan minat riset saya.',
            'Relevan dengan rencana karir saya ke depan.',
            'Ingin mendalami bidang ini lebih jauh.',
            'Sesuai kompetensi yang sudah saya miliki.',
            'Judul menarik dan menantang untuk dikerjakan.',
        ];

        $rows = [];
        $offset = 0;

        // Ambil 3 judul berbeda (langkah 7 & 13 relatif prima terhadap 25).
        $pick = function ($o) use ($juduls, $jN) {
            return [$juduls[$o % $jN], $juduls[($o + 7) % $jN], $juduls[($o + 13) % $jN]];
        };

        $make = function ($mhsId, $periode, $o, $outcome, $override = null) use (&$rows, $pick, $kalab, $prodi, $alasan) {
            [$p1, $p2, $p3] = $override ?? $pick($o);
            $buka = Carbon::parse($periode->tanggal_buka);
            $created = $buka->copy()->addDays(3 + ($o % 10))->setTime(9 + ($o % 8), 15);
            $revKalab = $created->copy()->addDays(2)->setTime(10, 30);
            $revProdi = $revKalab->copy()->addDays(2)->setTime(14, 0);
            $fmt = fn($c) => $c->format('Y-m-d H:i:s');

            $row = [
                'mahasiswa_id' => $mhsId,
                'judul_id' => null,
                'judul_mandiri' => null,
                'deskripsi_mandiri' => null,
                'jenis' => 'pilih',
                'prioritas' => null,
                'alasan' => null,
                'status' => 'pending',
                'catatan_dosen' => null,
                'periode_id' => $periode->id,
                // Review berjenjang: masuk lab prioritas-1 dulu.
                'prioritas_aktif' => 1,
                'lab_aktif_id' => $p1->laboratorium_id,
                'pilihan_1_id' => $p1->id,
                'pilihan_2_id' => $p2->id,
                'pilihan_3_id' => $p3->id,
                'alasan_1' => $alasan[$o % 5],
                'alasan_2' => $alasan[($o + 1) % 5],
                'alasan_3' => $alasan[($o + 2) % 5],
                'status_kalab' => null,
                'catatan_kalab_pengajuan' => null,
                'status_kaprodi' => null,
                'catatan_kaprodi' => null,
                'tanggal_kaprodi' => null,
                'judul_ditetapkan_id' => null,
                'sumber_judul' => null,
                'dosen_pembimbing_id' => null,
                'status_dosen' => null,
                'tanggal_review_kalab' => null,
                'reviewed_by_kalab' => null,
                'tanggal_review_kaprodi' => null,
                'reviewed_by_kaprodi' => null,
                'created_at' => $fmt($created),
                'updated_at' => $fmt($created),
            ];

            if ($outcome === 'approved') {
                $row = array_merge($row, [
                    'status' => 'disetujui',
                    'status_kalab' => 'disetujui',
                    'catatan_kalab_pengajuan' => 'Judul sesuai kompetensi laboratorium.',
                    'status_kaprodi' => 'disetujui',
                    'catatan_kaprodi' => 'Disetujui. Silakan mulai bimbingan dengan dosen pembimbing.',
                    'tanggal_kaprodi' => $fmt($revProdi),
                    'judul_ditetapkan_id' => $p1->id,
                    'sumber_judul' => 'pilihan_1',
                    'dosen_pembimbing_id' => $p1->dosen_id,
                    'tanggal_review_kalab' => $fmt($revKalab),
                    'reviewed_by_kalab' => $kalab,
                    'tanggal_review_kaprodi' => $fmt($revProdi),
                    'reviewed_by_kaprodi' => $prodi,
                    'updated_at' => $fmt($revProdi),
                ]);
            } elseif ($outcome === 'rejected_kalab') {
                $row = array_merge($row, [
                    'status' => 'ditolak',
                    'status_kalab' => 'ditolak',
                    'catatan_kalab_pengajuan' => 'Judul belum sesuai fokus lab, silakan ajukan judul lain.',
                    'tanggal_review_kalab' => $fmt($revKalab),
                    'reviewed_by_kalab' => $kalab,
                    'updated_at' => $fmt($revKalab),
                ]);
            } elseif ($outcome === 'rejected_prodi') {
                $row = array_merge($row, [
                    'status' => 'ditolak',
                    'status_kalab' => 'disetujui',
                    'catatan_kalab_pengajuan' => 'Judul sesuai kompetensi laboratorium.',
                    'status_kaprodi' => 'ditolak',
                    'catatan_kaprodi' => 'Belum dapat disetujui pada periode ini.',
                    'tanggal_kaprodi' => $fmt($revProdi),
                    'tanggal_review_kalab' => $fmt($revKalab),
                    'reviewed_by_kalab' => $kalab,
                    'tanggal_review_kaprodi' => $fmt($revProdi),
                    'reviewed_by_kaprodi' => $prodi,
                    'updated_at' => $fmt($revProdi),
                ]);
            } elseif ($outcome === 'kalab_approved') {
                // Sudah disetujui Ka Lab, MENUNGGU keputusan Prodi (untuk demo review live).
                $row = array_merge($row, [
                    'status' => 'pending',
                    'status_kalab' => 'disetujui',
                    'catatan_kalab_pengajuan' => 'Judul sesuai kompetensi laboratorium.',
                    'tanggal_review_kalab' => $fmt($revKalab),
                    'reviewed_by_kalab' => $kalab,
                    'updated_at' => $fmt($revKalab),
                ]);
            }
            // 'pending' → biarkan apa adanya (belum direview Ka Lab).

            $rows[] = $row;
        };

        // ============ PERIODE ARSIP (final, sudah diumumkan) ============
        // Jumlah divariasikan agar grafik tren antar-periode terlihat dinamis.
        $rencanaArsip = [
            // [indeks mahasiswa => outcome]
            [0 => 'approved', 1 => 'approved', 2 => 'approved', 3 => 'approved', 4 => 'rejected_kalab'],
            [0 => 'approved', 1 => 'approved', 2 => 'approved', 3 => 'approved', 4 => 'approved', 5 => 'rejected_prodi'],
            [0 => 'approved', 1 => 'approved', 2 => 'approved', 3 => 'approved', 4 => 'approved', 5 => 'approved', 6 => 'rejected_kalab'],
            [1 => 'approved', 2 => 'approved', 3 => 'approved', 4 => 'approved', 5 => 'approved', 6 => 'rejected_prodi'],
            [0 => 'approved', 1 => 'approved', 2 => 'approved', 3 => 'approved', 4 => 'approved', 5 => 'approved', 6 => 'rejected_kalab', 7 => 'approved'],
        ];

        foreach ($arsip as $i => $periode) {
            $rencana = $rencanaArsip[$i] ?? [];
            foreach ($rencana as $mi => $outcome) {
                if (!isset($mhs[$mi])) {
                    continue;
                }
                $make($mhs[$mi], $periode, $offset, $outcome);
                $offset++;
            }
        }

        // ============ PERIODE AKTIF (campur: siap demo) ============
        // 3 pending 'pilih' (Ka Lab review berjenjang) + 2 disetujui Ka Lab (Prodi review)
        // + 1 mandiri menunggu dosen + 1 mandiri sudah dikonfirmasi (antre Ka Lab) + 1 belum mengajukan.
        if ($aktif) {
            // Judul per lab (lab id: SIRKEL=1, ITSC=2, MVK=3, SIBER=4).
            $judulByLab = collect($juduls)->groupBy('laboratorium_id');
            $L = fn($labId, $skip = 0) => ($judulByLab[$labId] ?? collect())->values()->get($skip);

            // 3 pending 'pilih' — prioritas 1 di lab BERBEDA supaya tiap Ka Lab punya antrean,
            // dan prioritas 2/3 di lab lain agar cascade (tolak → lab berikutnya) bisa didemokan.
            if (isset($mhs[0])) {
                $make($mhs[0], $aktif, $offset++, 'pending', [$L(1), $L(2), $L(3)]); // SIRKEL→ITSC→MVK
            }
            if (isset($mhs[1])) {
                $make($mhs[1], $aktif, $offset++, 'pending', [$L(2), $L(3), $L(4)]); // ITSC→MVK→SIBER
            }
            if (isset($mhs[2])) {
                $make($mhs[2], $aktif, $offset++, 'pending', [$L(3), $L(4), $L(1)]); // MVK→SIBER→SIRKEL
            }
            // 2 sudah disetujui Ka Lab → menunggu keputusan Prodi.
            if (isset($mhs[3])) {
                $make($mhs[3], $aktif, $offset++, 'kalab_approved');
            }
            if (isset($mhs[4])) {
                $make($mhs[4], $aktif, $offset++, 'kalab_approved');
            }

            // ----- Contoh USULAN MANDIRI (butuh langkah konfirmasi dosen) -----
            $dosenList = DB::table('users')->where('role', 'dosen')->orderBy('id')->pluck('id')->all();
            $labSiber = DB::table('laboratorium')->where('nama', 'SISTEM SIBER')->value('id');
            $bukaAktif = Carbon::parse($aktif->tanggal_buka);
            $fmtM = fn($c) => $c->format('Y-m-d H:i:s');

            $baseMandiri = fn($mhsId, $extra) => array_merge([
                'mahasiswa_id' => $mhsId,
                'judul_id' => null,
                'judul_mandiri' => null,
                'deskripsi_mandiri' => null,
                'jenis' => 'mandiri',
                'prioritas' => null,
                'alasan' => null,
                'status' => 'pending',
                'catatan_dosen' => null,
                'periode_id' => $aktif->id,
                'prioritas_aktif' => 1,
                'lab_aktif_id' => null,
                'pilihan_1_id' => null,
                'pilihan_2_id' => null,
                'pilihan_3_id' => null,
                'alasan_1' => null,
                'alasan_2' => null,
                'alasan_3' => null,
                'status_kalab' => null,
                'catatan_kalab_pengajuan' => null,
                'status_kaprodi' => null,
                'catatan_kaprodi' => null,
                'tanggal_kaprodi' => null,
                'judul_ditetapkan_id' => null,
                'sumber_judul' => null,
                'dosen_pembimbing_id' => $dosenList[0] ?? null,
                'status_dosen' => null,
                'tanggal_review_kalab' => null,
                'reviewed_by_kalab' => null,
                'tanggal_review_kaprodi' => null,
                'reviewed_by_kaprodi' => null,
                'created_at' => $fmtM($bukaAktif->copy()->addDays(20)),
                'updated_at' => $fmtM($bukaAktif->copy()->addDays(20)),
            ], $extra);

            // mhs[5] (Fitri): mandiri MENUNGGU konfirmasi dosen (dosen pertama).
            if (isset($mhs[5])) {
                $rows[] = $baseMandiri($mhs[5], [
                    'judul_mandiri' => 'Sistem Rekomendasi Tempat Magang Berbasis Machine Learning',
                    'deskripsi_mandiri' => 'Rekomendasi tempat magang untuk mahasiswa berbasis preferensi & konten.',
                ]);
            }
            // mhs[6] (Gilang): mandiri SUDAH dikonfirmasi dosen → antre di Ka Lab (lab SIRKEL).
            if (isset($mhs[6])) {
                $rows[] = $baseMandiri($mhs[6], [
                    'judul_mandiri' => 'Aplikasi Deteksi Dini Stunting Berbasis Android',
                    'deskripsi_mandiri' => 'Skrining risiko stunting balita dengan input data antropometri.',
                    'status_dosen' => 'dikonfirmasi',
                    'lab_aktif_id' => $labSiber,
                ]);
            }
        }

        foreach (array_chunk($rows, 50) as $chunk) {
            DB::table('pengajuan')->insert($chunk);
        }
    }
}
