<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // DOSEN
        User::insert([
            [
                'name' => 'Dr. Budi',
                'email' => 'dosen1@mail.com',
                'password' => Hash::make('password'),
                'role' => 'dosen',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => 'Dr. Sari',
                'email' => 'dosen2@mail.com',
                'password' => Hash::make('password'),
                'role' => 'dosen',
                'created_at' => now(),
                'updated_at' => now()
            ],

            // MAHASISWA
            [
                'name' => 'Andi',
                'email' => 'mhs1@mail.com',
                'password' => Hash::make('password'),
                'role' => 'mahasiswa',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => 'Budi Mahasiswa',
                'email' => 'mhs2@mail.com',
                'password' => Hash::make('password'),
                'role' => 'mahasiswa',
                'created_at' => now(),
                'updated_at' => now()
            ]
        ]);
    }
}