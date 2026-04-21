<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class JudulSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('judul')->insert([
            [
                'kode' => 'SIRKEL-1',
                'nama_judul' => 'Smart PM Assistant',
                'deskripsi' => 'AI untuk manajemen proyek',
                'laboratorium_id' => 1,
                'dosen_id' => 1,
                'aktif' => DB::raw('true'),
                'is_locked' => DB::raw('false'),
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'kode' => 'ITSC-1',
                'nama_judul' => 'Fine Tuning Small LLM',
                'deskripsi' => 'Optimasi model AI kecil',
                'laboratorium_id' => 2,
                'dosen_id' => 2,
                'aktif' => DB::raw('true'),
                'is_locked' => DB::raw('false'),
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'kode' => 'MVK-1',
                'nama_judul' => 'Food Label Reader',
                'deskripsi' => 'Computer vision mobile',
                'laboratorium_id' => 3,
                'dosen_id' => 2,
                'aktif' => DB::raw('true'),
                'is_locked' => DB::raw('false'),
                'created_at' => now(),
                'updated_at' => now()
            ]
        ]);
    }
}