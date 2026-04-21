<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Laboratorium;

class LaboratoriumSeeder extends Seeder
{
    public function run(): void
    {
        Laboratorium::insert([
            ['nama' => 'SIRKEL', 'created_at' => now(), 'updated_at' => now()],
            ['nama' => 'ITSC', 'created_at' => now(), 'updated_at' => now()],
            ['nama' => 'MVK', 'created_at' => now(), 'updated_at' => now()],
            ['nama' => 'SS', 'created_at' => now(), 'updated_at' => now()],
        ]);
    }
}