<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('pengajuan', function (Blueprint $table) {
            // Status dari kaprodi (final approval)
            $table->string('status_kaprodi')->nullable()->after('catatan_dosen');
            // null (belum sampai kaprodi), pending, disetujui, ditolak

            // Catatan dari kaprodi
            $table->text('catatan_kaprodi')->nullable()->after('status_kaprodi');

            // Tanggal keputusan kaprodi
            $table->timestamp('tanggal_kaprodi')->nullable()->after('catatan_kaprodi');

            // Status workflow untuk judul mandiri (tracking koor & kalab)
            $table->string('status_koor')->nullable()->after('tanggal_kaprodi');
            $table->text('catatan_koor_pengajuan')->nullable()->after('status_koor');
            $table->string('status_kalab')->nullable()->after('catatan_koor_pengajuan');
            $table->text('catatan_kalab_pengajuan')->nullable()->after('status_kalab');
        });
    }

    public function down(): void
    {
        Schema::table('pengajuan', function (Blueprint $table) {
            $table->dropColumn([
                'status_kaprodi',
                'catatan_kaprodi',
                'tanggal_kaprodi',
                'status_koor',
                'catatan_koor_pengajuan',
                'status_kalab',
                'catatan_kalab_pengajuan'
            ]);
        });
    }
};
