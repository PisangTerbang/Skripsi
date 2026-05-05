<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PeriodeSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('periode')->truncate();

        DB::table('periode')->insert([
            [
                'semester' => 'genap',
                'tahun_ajaran' => '2025/2026',
                'aktif' => DB::raw('true'),
                'ditutup' => DB::raw('false'),
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
