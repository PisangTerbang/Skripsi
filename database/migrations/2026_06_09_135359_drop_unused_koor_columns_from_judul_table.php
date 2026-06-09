<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Drop kolom judul yang tidak dipakai:
     * - catatan_koor, koor_lab_id, tanggal_koor: sisa Koor Lab (dihapus dari workflow)
     * - jumlah_peminat: peminat dihitung lewat relasi (getTotalPeminatAttribute), kolom ini mati
     * Sudah diaudit: nol referensi aktif, tanpa FK.
     */
    public function up(): void
    {
        Schema::table('judul', function (Blueprint $table) {
            $table->dropColumn([
                'catatan_koor',
                'koor_lab_id',
                'tanggal_koor',
                'jumlah_peminat',
            ]);
        });
    }

    /**
     * Kembalikan kolom (reversible) bila migrasi di-rollback.
     */
    public function down(): void
    {
        Schema::table('judul', function (Blueprint $table) {
            $table->text('catatan_koor')->nullable();
            $table->unsignedBigInteger('koor_lab_id')->nullable();
            $table->timestamp('tanggal_koor')->nullable();
            $table->integer('jumlah_peminat')->default(0);
        });
    }
};
