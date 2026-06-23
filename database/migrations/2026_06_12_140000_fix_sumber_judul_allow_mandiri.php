<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

/**
 * Perbaikan: kolom pengajuan.sumber_judul punya CHECK yang hanya mengizinkan
 * 'usulan','pilihan_1','pilihan_2','pilihan_3' — padahal Ka Lab bisa memilih
 * 'mandiri' saat meng-acc (approveByKalab menyetel sumber_judul='mandiri' dan
 * view show membaca === 'mandiri'). Tanpa ini, meng-acc judul mandiri gagal
 * (check constraint violation). Tambahkan 'mandiri' ke daftar yang valid.
 */
return new class extends Migration
{
    public function up(): void
    {
        DB::statement('ALTER TABLE pengajuan DROP CONSTRAINT IF EXISTS pengajuan_sumber_judul_check');
        DB::statement("ALTER TABLE pengajuan ADD CONSTRAINT pengajuan_sumber_judul_check CHECK (sumber_judul IS NULL OR sumber_judul IN ('usulan','pilihan_1','pilihan_2','pilihan_3','mandiri'))");
    }

    public function down(): void
    {
        DB::statement('ALTER TABLE pengajuan DROP CONSTRAINT IF EXISTS pengajuan_sumber_judul_check');
        DB::statement("ALTER TABLE pengajuan ADD CONSTRAINT pengajuan_sumber_judul_check CHECK (sumber_judul IN ('usulan','pilihan_1','pilihan_2','pilihan_3'))");
    }
};
