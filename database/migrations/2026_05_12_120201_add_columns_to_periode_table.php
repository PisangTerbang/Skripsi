<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('periode', function (Blueprint $table) {
            // Jadwal buka-tutup pengajuan
            $table->date('tanggal_buka')->nullable()->after('tahun_akademik');
            $table->date('tanggal_tutup')->nullable()->after('tanggal_buka');

            // Status periode (hanya 1 periode yang bisa aktif)
            $table->boolean('is_active')->default(false)->after('tanggal_tutup');

            // Deskripsi/keterangan periode
            $table->text('keterangan')->nullable()->after('is_active');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('periode', function (Blueprint $table) {
            $table->dropColumn([
                'tanggal_buka',
                'tanggal_tutup',
                'is_active',
                'keterangan',
            ]);
        });
    }
};
