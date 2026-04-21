<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Pengajuan;

class PengajuanSeeder extends Seeder
{
    public function run(): void
    {
        // MAHASISWA 1 - PILIH JUDUL DOSEN
        Pengajuan::create([
            'mahasiswa_id' => 3,
            'judul_id' => 1,
            'jenis' => 'pilih',
            'prioritas' => 1,
            'alasan' => 'Suka AI',
            'status' => 'pending',
        ]);

        // MAHASISWA 1 - JUDUL MANDIRI
        Pengajuan::create([
            'mahasiswa_id' => 3,
            'judul_mandiri' => 'AI Deteksi Emosi',
            'deskripsi_mandiri' => 'Deteksi wajah berbasis CNN',
            'jenis' => 'mandiri',
            'prioritas' => 2,
            'status' => 'pending',
        ]);

        // MAHASISWA 2 - PILIH JUDUL DOSEN
        Pengajuan::create([
            'mahasiswa_id' => 4,
            'judul_id' => 1,
            'jenis' => 'pilih',
            'prioritas' => 2,
            'alasan' => 'Menarik',
            'status' => 'pending',
        ]);
    }
}