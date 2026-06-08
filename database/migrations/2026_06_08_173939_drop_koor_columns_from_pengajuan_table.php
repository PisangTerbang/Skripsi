<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Drop kolom Koor Lab yang tidak dipakai di workflow final.
     * Koor Lab dihapus dari alur; kolom ini sudah tidak direferensikan kode mana pun.
     */
    public function up(): void
    {
        Schema::table('pengajuan', function (Blueprint $table) {
            // Drop foreign key dulu sebelum kolomnya
            $table->dropForeign(['reviewed_by_koor']);

            $table->dropColumn([
                'status_koor',
                'catatan_koor_pengajuan',
                'tanggal_review_koor',
                'reviewed_by_koor',
            ]);
        });
    }

    /**
     * Kembalikan kolom Koor Lab (reversible) bila migrasi di-rollback.
     */
    public function down(): void
    {
        Schema::table('pengajuan', function (Blueprint $table) {
            $table->string('status_koor')->nullable();
            $table->text('catatan_koor_pengajuan')->nullable();
            $table->timestamp('tanggal_review_koor')->nullable();
            $table->foreignId('reviewed_by_koor')->nullable()
                ->constrained('users')->nullOnDelete();
        });
    }
};
