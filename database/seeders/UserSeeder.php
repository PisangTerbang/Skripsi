<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('users')->truncate();

        DB::table('users')->insert([
            // ============ DOSEN ============
            [
                'name' => 'Sri Mulyati, S.Kom., M.Kom.',
                'email' => 'srimulyati@dosen.com',
                'nim' => null,
                'password' => Hash::make('password'),
                'role' => 'dosen',
                'laboratorium_id' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Fayruz Rahma, S.T., M.Eng.',
                'email' => 'fayruz@dosen.com',
                'nim' => null,
                'password' => Hash::make('password'),
                'role' => 'dosen',
                'laboratorium_id' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Erika Ramadhani, S.T., M.Eng.',
                'email' => 'erika@dosen.com',
                'nim' => null,
                'password' => Hash::make('password'),
                'role' => 'dosen',
                'laboratorium_id' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Yudi Prayudi, Dr., S.Si., M.Kom.',
                'email' => 'yudi@dosen.com',
                'nim' => null,
                'password' => Hash::make('password'),
                'role' => 'dosen',
                'laboratorium_id' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],

            // ============ KEPALA LAB (KA LAB) ============
            // Merge dari koor_lab (4 org) + kepala_lab (1 org) menjadi 1 org
            [
                'name' => 'Dr. Ahmad Fauzi, M.Kom.',
                'email' => 'kalab@informatika.com',
                'nim' => null,
                'password' => Hash::make('password'),
                'role' => 'ka_lab',
                'laboratorium_id' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],

            // ============ PRODI (DULU KAPRODI) ============
            [
                'name' => 'Dr. Budi Santoso, M.T.',
                'email' => 'prodi@informatika.com',
                'nim' => null,
                'password' => Hash::make('password'),
                'role' => 'prodi',
                'laboratorium_id' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],

            // ============ KOORDINATOR TA (ROLE BARU - ADMIN) ============
            [
                'name' => 'Admin Koordinator TA',
                'email' => 'koordinatorta@informatika.com',
                'nim' => null,
                'password' => Hash::make('password'),
                'role' => 'koordinator_ta',
                'laboratorium_id' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],

            // ============ MAHASISWA ============
            [
                'name' => 'Andi',
                'email' => 'mhs1@mail.com',
                'nim' => '22523001',
                'password' => Hash::make('password'),
                'role' => 'mahasiswa',
                'laboratorium_id' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Budi',
                'email' => 'mhs2@mail.com',
                'nim' => '22523002',
                'password' => Hash::make('password'),
                'role' => 'mahasiswa',
                'laboratorium_id' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Citra',
                'email' => 'mhs3@mail.com',
                'nim' => '22523003',
                'password' => Hash::make('password'),
                'role' => 'mahasiswa',
                'laboratorium_id' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Dani',
                'email' => 'mhs4@mail.com',
                'nim' => '22523004',
                'password' => Hash::make('password'),
                'role' => 'mahasiswa',
                'laboratorium_id' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
