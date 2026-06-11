<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;

/**
 * Fitur chat konsultasi mahasiswa<->dosen dihapus dari sistem.
 * Tabel chat tidak lagi digunakan, jadi di-drop agar struktur steril.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::dropIfExists('messages');
        Schema::dropIfExists('conversations');
    }

    public function down(): void
    {
        // Tabel chat sengaja tidak dibuat ulang (fitur dihentikan).
        // Migration create_conversations_table / create_messages_table lama
        // tetap ada di histori bila suatu saat diperlukan kembali.
    }
};
