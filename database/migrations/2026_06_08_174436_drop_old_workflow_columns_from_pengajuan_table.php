<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Drop kolom sisa workflow lama (satu-pilihan) yang sudah tidak
     * direferensikan kode mana pun. Sudah diaudit: nol referensi, nol data.
     */
    public function up(): void
    {
        Schema::table('pengajuan', function (Blueprint $table) {
            // Drop foreign key dulu sebelum kolomnya
            $table->dropForeign(['dosen_pilihan_id']);

            $table->dropColumn([
                'dosen_pilihan_id',
                'status_ta',
                'tanggal_mulai',
                'tanggal_selesai',
            ]);
        });
    }

    /**
     * Kembalikan kolom (reversible) bila migrasi di-rollback.
     */
    public function down(): void
    {
        Schema::table('pengajuan', function (Blueprint $table) {
            $table->string('status_ta')->default('belum_mulai');
            $table->date('tanggal_mulai')->nullable();
            $table->date('tanggal_selesai')->nullable();
            $table->foreignId('dosen_pilihan_id')->nullable()
                ->constrained('users')->nullOnDelete();
        });
    }
};
