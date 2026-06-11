<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Konsep "kuota" dihentikan:
 * - 1 judul hanya untuk 1 mahasiswa (dikunci via is_locked), jadi kuota_maksimal tak relevan.
 * - Batas kuota bimbingan dosen diganti tampilan "jumlah mahasiswa dibimbing" (dihitung otomatis).
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            if (Schema::hasColumn('users', 'kuota_bimbingan')) {
                $table->dropColumn('kuota_bimbingan');
            }
        });

        Schema::table('judul', function (Blueprint $table) {
            if (Schema::hasColumn('judul', 'kuota_maksimal')) {
                $table->dropColumn('kuota_maksimal');
            }
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->unsignedInteger('kuota_bimbingan')->nullable();
        });

        Schema::table('judul', function (Blueprint $table) {
            $table->integer('kuota_maksimal')->nullable();
        });
    }
};
