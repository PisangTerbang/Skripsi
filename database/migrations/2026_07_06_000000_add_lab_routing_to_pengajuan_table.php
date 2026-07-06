<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('pengajuan', function (Blueprint $table) {
            // Prioritas judul yang sedang direview Ka Lab (review berjenjang 1..3).
            $table->unsignedTinyInteger('prioritas_aktif')->default(1)->after('prioritas');
            // Lab yang sedang menangani pengajuan (antrean Ka Lab per-lab).
            $table->unsignedBigInteger('lab_aktif_id')->nullable()->after('prioritas_aktif');
            // Khusus usulan mandiri: null=menunggu dosen, dikonfirmasi, ditolak.
            $table->string('status_dosen')->nullable()->after('dosen_pembimbing_id');

            $table->foreign('lab_aktif_id')->references('id')->on('laboratorium')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('pengajuan', function (Blueprint $table) {
            $table->dropForeign(['lab_aktif_id']);
            $table->dropColumn(['prioritas_aktif', 'lab_aktif_id', 'status_dosen']);
        });
    }
};
