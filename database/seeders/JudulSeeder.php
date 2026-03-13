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
                'nama_judul' => 'Smart PM Assistant: Pembangunan Kanban Board Otomatis Untuk Notulensi Rapat',
                'deskripsi' => 'Memahami konsep SCRUM dan integrasi API',
                'laboratorium_id' => 1,
                'dosen_id' => 1,
                'peminat' => 0,
                'aktif' => DB::raw('true')
            ],

            [
                'kode' => 'ITSC-1',
                'nama_judul' => 'Fine Tuning Small Language Model untuk Chatbot Kesehatan Offline',
                'deskripsi' => 'Memahami konsep LLM',
                'laboratorium_id' => 1,
                'dosen_id' => 1,
                'peminat' => 0,
                'aktif' => DB::raw('true')
            ],

            [
                'kode' => 'SIRKEL-2',
                'nama_judul' => 'Text-to-BPMN: Otomasi pemodelan proses bisnis untuk Camunda',
                'deskripsi' => 'Memahami BPMN',
                'laboratorium_id' => 1,
                'dosen_id' => 1,
                'peminat' => 0,
                'aktif' => DB::raw('true')
            ],

            [
                'kode' => 'SIRKEL-3',
                'nama_judul' => 'Smart Interview Assistant: Real-time Interview Director In Building User Persona',
                'deskripsi' => 'Memahami konsep persona',
                'laboratorium_id' => 1,
                'dosen_id' => 1,
                'peminat' => 0,
                'aktif' => DB::raw('true')
            ],

            [
                'kode' => 'MVK-1',
                'nama_judul' => 'Food Label Reader: Aplikasi Klasifikasi Label Nutrisi dan Ingredients berbasis Mobile',
                'deskripsi' => 'Vision Language Model',
                'laboratorium_id' => 1,
                'dosen_id' => 1,
                'peminat' => 0,
                'aktif' => DB::raw('true')
            ]

        ]);

    }
}