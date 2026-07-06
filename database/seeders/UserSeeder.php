<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // Catatan: pembersihan tabel dilakukan terpusat di DatabaseSeeder (urutan FK aman).
        $pw = Hash::make('password');

        DB::table('users')->insert([
            // ============ DOSEN ============
            [
                'name' => 'Sri Mulyati, S.Kom., M.Kom.',
                'email' => 'srimulyati@dosen.com',
                'nim' => null,
                'password' => $pw,
                'role' => 'dosen',
                'laboratorium_id' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Fayruz Rahma, S.T., M.Eng.',
                'email' => 'fayruz@dosen.com',
                'nim' => null,
                'password' => $pw,
                'role' => 'dosen',
                'laboratorium_id' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Erika Ramadhani, S.T., M.Eng.',
                'email' => 'erika@dosen.com',
                'nim' => null,
                'password' => $pw,
                'role' => 'dosen',
                'laboratorium_id' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Dr. Yudi Prayudi, S.Si., M.Kom.',
                'email' => 'yudi@dosen.com',
                'nim' => null,
                'password' => $pw,
                'role' => 'dosen',
                'laboratorium_id' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],

            // ============ KEPALA LAB (KA LAB) — 1 per laboratorium ============
            // laboratorium_id: SIRKEL=1, ITSC=2, MVK=3, SISTEM SIBER=4
            [
                'name' => 'Dr. Ahmad Fauzi, M.Kom.',
                'email' => 'kalab.sirkel@informatika.com',
                'nim' => null,
                'password' => $pw,
                'role' => 'ka_lab',
                'laboratorium_id' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Dr. Rina Kartika, M.Cs.',
                'email' => 'kalab.itsc@informatika.com',
                'nim' => null,
                'password' => $pw,
                'role' => 'ka_lab',
                'laboratorium_id' => 2,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Dr. Bagus Nugroho, M.Kom.',
                'email' => 'kalab.mvk@informatika.com',
                'nim' => null,
                'password' => $pw,
                'role' => 'ka_lab',
                'laboratorium_id' => 3,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Dr. Sinta Maharani, M.T.',
                'email' => 'kalab.siber@informatika.com',
                'nim' => null,
                'password' => $pw,
                'role' => 'ka_lab',
                'laboratorium_id' => 4,
                'created_at' => now(),
                'updated_at' => now(),
            ],

            // ============ PRODI (KAPRODI) ============
            [
                'name' => 'Dr. Budi Santoso, M.T.',
                'email' => 'prodi@informatika.com',
                'nim' => null,
                'password' => $pw,
                'role' => 'prodi',
                'laboratorium_id' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],

            // ============ KOORDINATOR TA (ADMIN) ============
            [
                'name' => 'Admin Koordinator TA',
                'email' => 'koordinatorta@informatika.com',
                'nim' => null,
                'password' => $pw,
                'role' => 'koordinator_ta',
                'laboratorium_id' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);

        // ============ MAHASISWA (8 orang, login lama dipertahankan) ============
        $mahasiswa = [
            ['Andi Pratama',      'mhs1@mail.com', '22523001'],
            ['Budi Setiawan',     'mhs2@mail.com', '22523002'],
            ['Citra Lestari',     'mhs3@mail.com', '22523003'],
            ['Dani Ramadhan',     'mhs4@mail.com', '22523004'],
            ['Eka Wijaya',        'mhs5@mail.com', '22523005'],
            ['Fitri Handayani',   'mhs6@mail.com', '22523006'],
            ['Gilang Saputra',    'mhs7@mail.com', '22523007'],
            ['Hana Permata',      'mhs8@mail.com', '22523008'],
        ];

        DB::table('users')->insert(array_map(fn($m) => [
            'name' => $m[0],
            'email' => $m[1],
            'nim' => $m[2],
            'password' => $pw,
            'role' => 'mahasiswa',
            'laboratorium_id' => null,
            'created_at' => now(),
            'updated_at' => now(),
        ], $mahasiswa));
    }
}
