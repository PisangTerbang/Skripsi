<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PeriodeSeeder extends Seeder
{
    public function run(): void
    {
        // 5 periode ARSIP (sudah ditutup & diumumkan) + 1 periode AKTIF (sedang dibuka).
        // Boolean ditulis literal DB::raw agar cocok dengan tipe boolean PostgreSQL.
        // Tanggal periode aktif dibuat mencakup "hari ini" agar terbuka untuk pengajuan saat demo.
        $periode = [
            ['Semester Ganjil 2023/2024', 'ganjil', '2023/2024', '2023-08-01', '2024-01-31', false],
            ['Semester Genap 2023/2024',  'genap',  '2023/2024', '2024-02-01', '2024-07-31', false],
            ['Semester Ganjil 2024/2025', 'ganjil', '2024/2025', '2024-08-01', '2025-01-31', false],
            ['Semester Genap 2024/2025',  'genap',  '2024/2025', '2025-02-01', '2025-07-31', false],
            ['Semester Ganjil 2025/2026', 'ganjil', '2025/2026', '2025-08-01', '2026-01-31', false],
            ['Semester Genap 2025/2026',  'genap',  '2025/2026', '2026-06-15', '2026-08-15', true],
        ];

        $rows = array_map(function ($p) {
            [$nama, $semester, $tahun, $buka, $tutup, $aktif] = $p;
            return [
                'nama' => $nama,
                'semester' => $semester,
                'tahun_ajaran' => $tahun,
                'tahun_akademik' => $tahun,
                'tanggal_buka' => $buka,
                'tanggal_tutup' => $tutup,
                'is_active' => DB::raw($aktif ? 'true' : 'false'),
                'aktif' => DB::raw($aktif ? 'true' : 'false'),
                'ditutup' => DB::raw($aktif ? 'false' : 'true'),
                'keterangan' => null,
                'created_at' => $buka . ' 08:00:00',
                'updated_at' => now(),
            ];
        }, $periode);

        foreach ($rows as $row) {
            DB::table('periode')->insert($row);
        }
    }
}
