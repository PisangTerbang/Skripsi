<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class LaboratoriumSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('laboratorium')->truncate();

        DB::table('laboratorium')->insert([
            [
                'id' => 1,
                'nama' => 'SIRKEL',
                'deskripsi' => 'Sistem Informasi dan Rekayasa Perangkat Lunak',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => 2,
                'nama' => 'ITSC',
                'deskripsi' => 'Informatika Teori dan Sistem Cerdas',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => 3,
                'nama' => 'MVK',
                'deskripsi' => 'Multimedia dan Visi Komputer',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => 4,
                'nama' => 'SISTEM SIBER',
                'deskripsi' => 'Sistem Siber',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
