<?php

namespace Database\Seeders;

use App\Models\Judul;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

/**
 * Seeder DEMO: periode arsip + 1 periode berjalan, dengan statistik pengajuan yang
 * berbeda-beda — untuk mengisi grafik/tren dashboard saat sidang.
 *
 * Mengikuti aturan asli pengajuan mahasiswa:
 *  - WAJIB 3 pilihan judul (pilihan_1/2/3) yang berbeda.
 *  - Usulan mandiri OPSIONAL: bila ada, jenis='mandiri', dosen_pembimbing_id wajib,
 *    namun 3 pilihan tetap diisi.
 *
 * Idempoten: periode dengan nama sama dilewati. Tidak menyentuh periode aktif/kunci judul.
 */
class DemoStatistikSeeder extends Seeder
{
    private array $pool = [];
    private int $pick = 0;

    public function run(): void
    {
        $kalab = User::where('role', 'ka_lab')->value('id');
        $prodi = User::where('role', 'prodi')->value('id');
        $koor = User::where('role', 'koor_ta')->value('id') ?? $kalab;
        $dosenIds = User::where('role', 'dosen')->pluck('id')->all();

        $this->pool = Judul::where('status_judul', 'ditawarkan')->orderBy('id')->pluck('id')->all();
        if (count($this->pool) < 3) {
            $this->command?->error('Butuh minimal 3 judul ditawarkan untuk membuat pilihan. Batal.');
            return;
        }

        // stage: setuju | tolak_kalab | tolak_kaprodi | proses | pending
        // mandiri: true bila mahasiswa juga mengajukan usulan mandiri.
        $data = [
            ['nama' => 'Semester Genap 2027/2028', 'buka' => '2028-02-01', 'tutup' => '2028-06-30', 'announce' => true, 'rows' => [
                ['m' => 8, 'stage' => 'setuju', 'mandiri' => false],
                ['m' => 9, 'stage' => 'setuju', 'mandiri' => true],
                ['m' => 10, 'stage' => 'setuju', 'mandiri' => false],
                ['m' => 11, 'stage' => 'setuju', 'mandiri' => false],
            ]],
            ['nama' => 'Semester Ganjil 2028/2029', 'buka' => '2028-08-01', 'tutup' => '2028-12-31', 'announce' => true, 'rows' => [
                ['m' => 8, 'stage' => 'setuju', 'mandiri' => false],
                ['m' => 9, 'stage' => 'setuju', 'mandiri' => false],
                ['m' => 10, 'stage' => 'tolak_kalab', 'mandiri' => true],
            ]],
            ['nama' => 'Semester Genap 2028/2029', 'buka' => '2029-02-01', 'tutup' => '2029-06-30', 'announce' => true, 'rows' => [
                ['m' => 8, 'stage' => 'setuju', 'mandiri' => false],
                ['m' => 9, 'stage' => 'tolak_kalab', 'mandiri' => false],
                ['m' => 10, 'stage' => 'tolak_kalab', 'mandiri' => true],
                ['m' => 11, 'stage' => 'tolak_kaprodi', 'mandiri' => false],
            ]],
            ['nama' => 'Semester Ganjil 2029/2030', 'buka' => '2029-08-01', 'tutup' => '2029-12-31', 'announce' => true, 'rows' => [
                ['m' => 8, 'stage' => 'setuju', 'mandiri' => true],
                ['m' => 9, 'stage' => 'setuju', 'mandiri' => false],
                ['m' => 10, 'stage' => 'setuju', 'mandiri' => false],
                ['m' => 11, 'stage' => 'tolak_kaprodi', 'mandiri' => false],
            ]],
            ['nama' => 'Semester Genap 2029/2030', 'buka' => '2030-02-01', 'tutup' => '2030-06-30', 'announce' => true, 'rows' => [
                ['m' => 8, 'stage' => 'setuju', 'mandiri' => false],
                ['m' => 9, 'stage' => 'tolak_kalab', 'mandiri' => false],
                ['m' => 10, 'stage' => 'setuju', 'mandiri' => true],
                ['m' => 11, 'stage' => 'tolak_kaprodi', 'mandiri' => false],
            ]],
            // Periode BERJALAN (belum diumumkan) — cocok diaktifkan untuk demo dashboard "live".
            ['nama' => 'Semester Ganjil 2030/2031', 'buka' => '2030-08-01', 'tutup' => '2030-12-31', 'announce' => false, 'rows' => [
                ['m' => 8, 'stage' => 'setuju', 'mandiri' => false],
                ['m' => 9, 'stage' => 'proses', 'mandiri' => true],
                ['m' => 10, 'stage' => 'pending', 'mandiri' => false],
                ['m' => 11, 'stage' => 'tolak_kalab', 'mandiri' => false],
            ]],
        ];

        foreach ($data as $p) {
            if (DB::table('periode')->where('nama', $p['nama'])->exists()) {
                $this->command?->warn("Lewati (sudah ada): {$p['nama']}");
                continue;
            }

            $buka = Carbon::parse($p['buka']);
            $tutup = Carbon::parse($p['tutup']);

            DB::transaction(function () use ($p, $buka, $tutup, $kalab, $prodi, $koor, $dosenIds) {
                $periodeId = DB::table('periode')->insertGetId([
                    'nama' => $p['nama'],
                    'tanggal_buka' => $buka,
                    'tanggal_tutup' => $tutup,
                    'is_active' => DB::raw('false'),
                    'aktif' => DB::raw('false'),
                    'ditutup' => DB::raw($p['announce'] ? 'true' : 'false'),
                    'created_at' => $buka,
                    'updated_at' => $tutup,
                ]);

                foreach ($p['rows'] as $i => $r) {
                    DB::table('pengajuan')->insert(
                        $this->buildPengajuan($r, $periodeId, $buka, $kalab, $prodi, $dosenIds[$i % count($dosenIds)])
                    );
                }

                if ($p['announce']) {
                    DB::table('pengumuman')->insert([
                        'periode_id' => $periodeId,
                        'dibuat_oleh' => $koor,
                        'judul' => 'Hasil Penetapan Judul — ' . $p['nama'],
                        'isi' => 'Hasil penetapan judul tugas akhir untuk ' . $p['nama'] . ' telah ditetapkan.',
                        'dikirim_at' => $tutup,
                        'created_at' => $tutup,
                        'updated_at' => $tutup,
                    ]);
                }

                $tag = $p['announce'] ? 'arsip' : 'BERJALAN';
                $this->command?->info("Dibuat [{$tag}]: {$p['nama']} (id={$periodeId}) — " . count($p['rows']) . ' pengajuan');
            });
        }
    }

    /**
     * Ambil 3 judul berbeda dari pool (berputar) untuk pilihan 1/2/3.
     */
    private function nextPilihan(): array
    {
        $n = count($this->pool);
        $base = ($this->pick * 3) % $n;
        $this->pick++;

        return [
            $this->pool[$base % $n],
            $this->pool[($base + 1) % $n],
            $this->pool[($base + 2) % $n],
        ];
    }

    private function buildPengajuan(array $r, int $periodeId, Carbon $buka, $kalab, $prodi, int $dosenPembimbing): array
    {
        [$p1, $p2, $p3] = $this->nextPilihan();
        $mandiri = $r['mandiri'] ?? false;

        $base = [
            'mahasiswa_id' => $r['m'],
            'periode_id' => $periodeId,
            'jenis' => $mandiri ? 'mandiri' : 'pilih',
            'prioritas' => 1,
            'pilihan_1_id' => $p1,
            'pilihan_2_id' => $p2,
            'pilihan_3_id' => $p3,
            'alasan_1' => 'Paling sesuai minat dan rencana penelitian.',
            'alasan_2' => 'Topik menarik sebagai alternatif.',
            'alasan_3' => 'Cadangan bila pilihan lain penuh.',
            'sumber_judul' => $mandiri ? 'usulan' : 'pilihan_1',
            'status' => 'pending',
            'status_kalab' => null,
            'status_kaprodi' => null,
            'judul_ditetapkan_id' => null,
            'created_at' => $buka->copy()->addDays(5),
            'updated_at' => $buka->copy()->addDays(20),
        ];

        if ($mandiri) {
            $base['judul_mandiri'] = 'Usulan Mandiri: Sistem Cerdas Pendukung Keputusan';
            $base['deskripsi_mandiri'] = 'Rancang bangun sistem berbasis data untuk membantu pengambilan keputusan.';
            $base['dosen_pembimbing_id'] = $dosenPembimbing;
        }

        $reviewKalab = $buka->copy()->addDays(10);
        $reviewKaprodi = $buka->copy()->addDays(20);
        // Judul yang ditetapkan: untuk 'pilih' = pilihan_1; untuk mandiri = null (judul kustom).
        $ditetapkan = $mandiri ? null : $p1;

        switch ($r['stage']) {
            case 'setuju':
                return array_merge($base, [
                    'status' => 'disetujui',
                    'status_kalab' => 'disetujui',
                    'status_kaprodi' => 'disetujui',
                    'judul_ditetapkan_id' => $ditetapkan,
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
                    'judul_ditetapkan_id' => $ditetapkan,
                    'catatan_kaprodi' => 'Kuota bimbingan dosen sudah penuh.',
                    'reviewed_by_kalab' => $kalab,
                    'tanggal_review_kalab' => $reviewKalab,
                    'reviewed_by_kaprodi' => $prodi,
                    'tanggal_review_kaprodi' => $reviewKaprodi,
                ]);

            case 'proses':
                // Sudah disetujui Ka Lab, menunggu keputusan Prodi.
                return array_merge($base, [
                    'status' => 'pending',
                    'status_kalab' => 'disetujui',
                    'reviewed_by_kalab' => $kalab,
                    'tanggal_review_kalab' => $reviewKalab,
                ]);

            case 'pending':
            default:
                // Baru masuk, menunggu review Ka Lab.
                return $base;
        }
    }
}
