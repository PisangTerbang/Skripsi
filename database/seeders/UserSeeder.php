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

            // ============ KOORDINATOR LAB ============
            [
                'name' => 'Koordinator Lab SIRKEL',
                'email' => 'koorlab@sirkel.com',
                'nim' => null,
                'password' => Hash::make('password'),
                'role' => 'koor_lab',
                'laboratorium_id' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Koordinator Lab ITSC',
                'email' => 'koorlab@itsc.com',
                'nim' => null,
                'password' => Hash::make('password'),
                'role' => 'koor_lab',
                'laboratorium_id' => 2,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Koordinator Lab MVK',
                'email' => 'koorlab@mvk.com',
                'nim' => null,
                'password' => Hash::make('password'),
                'role' => 'koor_lab',
                'laboratorium_id' => 3,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Koordinator Lab SISTEM SIBER',
                'email' => 'koorlab@siber.com',
                'nim' => null,
                'password' => Hash::make('password'),
                'role' => 'koor_lab',
                'laboratorium_id' => 4,
                'created_at' => now(),
                'updated_at' => now(),
            ],

            // ============ KEPALA LAB ============
            [
                'name' => 'Kepala Laboratorium',
                'email' => 'kalab@informatika.com',
                'nim' => null,
                'password' => Hash::make('password'),
                'role' => 'kepala_lab',
                'laboratorium_id' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],

            // ============ KAPRODI ============
            [
                'name' => 'Kepala Program Studi',
                'email' => 'kaprodi@informatika.com',
                'nim' => null,
                'password' => Hash::make('password'),
                'role' => 'kaprodi',
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
