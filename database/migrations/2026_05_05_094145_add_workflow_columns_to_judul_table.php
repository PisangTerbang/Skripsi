<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('judul', function (Blueprint $table) {
            // Status workflow judul
            $table->string('status_judul')->default('draft')->after('is_locked');
            // draft, pending_koor, pending_kalab, ditawarkan, ditolak_kalab

            // catatan dari koor lab
            $table->text('catatan_koor')->nullable()->after('status_judul');

            // Catatan dari kepala lab
            $table->text('catatan_kalab')->nullable()->after('catatan_koor');

            // ID koor lab yang mengelompokkan
            $table->unsignedBigInteger('koor_lab_id')->nullable()->after('catatan_kalab');

            // Tanggal dikelompokkan oleh koor
            $table->timestamp('tanggal_koor')->nullable()->after('koor_lab_id');

            // Tanggal divalidasi oleh kalab
            $table->timestamp('tanggal_kalab')->nullable()->after('tanggal_koor');

            // Relevant skills
            $table->text('relevant_skills')->nullable()->after('deskripsi');

            // catatan penting (important notes)
            $table->text('catatan_penting')->nullable()->after('relevant_skills');
        });
    }

    public function down(): void
    {
        Schema::table('judul', function (Blueprint $table) {
            $table->dropColumn([
                'status_judul',
                'catatan_koor',
                'catatan_kalab',
                'koor_lab_id',
                'tanggal_koor',
                'tanggal_kalab',
                'relevant_skills',
                'catatan_penting'
            ]);
        });
    }
};
