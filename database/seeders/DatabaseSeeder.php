<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Bersihkan seluruh data demo lama sekaligus. CASCADE menangani urutan FK,
        // RESTART IDENTITY mereset ID agar mulai rapi dari 1.
        DB::statement('TRUNCATE TABLE pengajuan, pengumuman, judul_logs, aktivitas, judul, periode, users, laboratorium RESTART IDENTITY CASCADE');

        $this->call([
            LaboratoriumSeeder::class,
            UserSeeder::class,
            PeriodeSeeder::class,
            JudulSeeder::class,
            PengajuanSeeder::class,
            PengumumanSeeder::class,
        ]);
    }
}
