<?php

namespace Database\Seeders;

use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

/**
 * Seeder DEMO: 4 periode arsip (non-aktif, sudah berpengumuman) dengan statistik
 * pengajuan yang berbeda-beda — untuk mengisi grafik/tren dashboard saat sidang.
 *
 * Tidak menyentuh periode aktif maupun kunci judul (is_locked dikelola per periode
 * aktif). Idempoten: periode dengan nama sama dilewati agar tak dobel saat diulang.
 */
class DemoStatistikSeeder extends Seeder
{
    public function run(): void
    {
        $kalab = User::where('role', 'ka_lab')->value('id');
        $prodi = User::where('role', 'prodi')->value('id');
        $koor = User::where('role', 'koor_ta')->value('id') ?? $kalab;

        // Tiap baris: m=mahasiswa_id, j=judul_id, stage=tahap keputusan.
        $data = [
            [
                'nama' => 'Semester Genap 2027/2028',
                'buka' => '2028-02-01', 'tutup' => '2028-06-30',
                'rows' => [
                    ['m' => 8, 'j' => 10, 'stage' => 'setuju'],
                    ['m' => 9, 'j' => 12, 'stage' => 'setuju'],
                    ['m' => 10, 'j' => 15, 'stage' => 'setuju'],
                    ['m' => 11, 'j' => 16, 'stage' => 'setuju'],
                ],
            ],
            [
                'nama' => 'Semester Ganjil 2028/2029',
                'buka' => '2028-08-01', 'tutup' => '2028-12-31',
                'rows' => [
                    ['m' => 8, 'j' => 13, 'stage' => 'setuju'],
                    ['m' => 9, 'j' => 17, 'stage' => 'setuju'],
                    ['m' => 10, 'j' => 14, 'stage' => 'tolak_kalab'],
                ],
            ],
            [
                'nama' => 'Semester Genap 2028/2029',
                'buka' => '2029-02-01', 'tutup' => '2029-06-30',
                'rows' => [
                    ['m' => 8, 'j' => 18, 'stage' => 'setuju'],
                    ['m' => 9, 'j' => 10, 'stage' => 'tolak_kalab'],
                    ['m' => 10, 'j' => 12, 'stage' => 'tolak_kalab'],
                    ['m' => 11, 'j' => 15, 'stage' => 'tolak_kaprodi'],
                ],
            ],
            [
                'nama' => 'Semester Ganjil 2029/2030',
                'buka' => '2029-08-01', 'tutup' => '2029-12-31',
                'rows' => [
                    ['m' => 8, 'j' => 16, 'stage' => 'setuju'],
                    ['m' => 9, 'j' => 13, 'stage' => 'setuju'],
                    ['m' => 10, 'j' => 17, 'stage' => 'setuju'],
                    ['m' => 11, 'j' => 14, 'stage' => 'tolak_kaprodi'],
                ],
            ],
        ];

        foreach ($data as $p) {
            if (DB::table('periode')->where('nama', $p['nama'])->exists()) {
                $this->command?->warn("Lewati (sudah ada): {$p['nama']}");
                continue;
            }

            $buka = Carbon::parse($p['buka']);
            $tutup = Carbon::parse($p['tutup']);

            DB::transaction(function () use ($p, $buka, $tutup, $kalab, $prodi, $koor) {
                $periodeId = DB::table('periode')->insertGetId([
                    'nama' => $p['nama'],
                    'tanggal_buka' => $buka,
                    'tanggal_tutup' => $tutup,
                    'is_active' => DB::raw('false'),
                    'aktif' => DB::raw('false'),
                    'ditutup' => DB::raw('true'),   // arsip = sudah ditutup
                    'created_at' => $buka,
                    'updated_at' => $tutup,
                ]);

                foreach ($p['rows'] as $r) {
                    DB::table('pengajuan')->insert(
                        $this->buildPengajuan($r, $periodeId, $buka, $kalab, $prodi)
                    );
                }

                // Pengumuman terkirim untuk tiap arsip (hasil sudah diumumkan).
                DB::table('pengumuman')->insert([
                    'periode_id' => $periodeId,
                    'dibuat_oleh' => $koor,
                    'judul' => 'Hasil Penetapan Judul — ' . $p['nama'],
                    'isi' => 'Hasil penetapan judul tugas akhir untuk ' . $p['nama'] . ' telah ditetapkan.',
                    'dikirim_at' => $tutup,
                    'created_at' => $tutup,
                    'updated_at' => $tutup,
                ]);

                $this->command?->info("Dibuat: {$p['nama']} (id={$periodeId}) — " . count($p['rows']) . ' pengajuan');
            });
        }
    }

    private function buildPengajuan(array $r, int $periodeId, Carbon $buka, $kalab, $prodi): array
    {
        $base = [
            'mahasiswa_id' => $r['m'],
            'periode_id' => $periodeId,
            'jenis' => 'pilih',
            'prioritas' => 1,
            'pilihan_1_id' => $r['j'],
            'alasan_1' => 'Sesuai minat dan rencana penelitian.',
            'sumber_judul' => 'pilihan_1',
            'status' => 'pending',
            'status_kalab' => null,
            'status_kaprodi' => null,
            'judul_ditetapkan_id' => null,
            'created_at' => $buka->copy()->addDays(5),
            'updated_at' => $buka->copy()->addDays(20),
        ];

        $reviewKalab = $buka->copy()->addDays(10);
        $reviewKaprodi = $buka->copy()->addDays(20);

        switch ($r['stage']) {
            case 'setuju':
                return array_merge($base, [
                    'status' => 'disetujui',
                    'status_kalab' => 'disetujui',
                    'status_kaprodi' => 'disetujui',
                    'judul_ditetapkan_id' => $r['j'],
                    'reviewed_by_kalab' => $kalab,
                    'tanggal_review_kalab' => $reviewKalab,
                    'reviewed_by_kaprodi' => $prodi,
                    'tanggal_review_kaprodi' => $reviewKaprodi,
                ]);

            case 'tolak_kalab':
                return array_merge($base, [
                    'status' => 'ditolak',
                    'status_kalab' => 'ditolak',
                    'catatan_kalab_pengajuan' => 'Topik tidak sesuai roadmap laboratorium.',
                    'reviewed_by_kalab' => $kalab,
                    'tanggal_review_kalab' => $reviewKalab,
                ]);

            case 'tolak_kaprodi':
                return array_merge($base, [
                    'status' => 'ditolak',
                    'status_kalab' => 'disetujui',
                    'status_kaprodi' => 'ditolak',
                    'judul_ditetapkan_id' => $r['j'],
                    'catatan_kaprodi' => 'Kuota bimbingan dosen sudah penuh.',
                    'reviewed_by_kalab' => $kalab,
                    'tanggal_review_kalab' => $reviewKalab,
                    'reviewed_by_kaprodi' => $prodi,
                    'tanggal_review_kaprodi' => $reviewKaprodi,
                ]);
        }

        return $base;
    }
}
