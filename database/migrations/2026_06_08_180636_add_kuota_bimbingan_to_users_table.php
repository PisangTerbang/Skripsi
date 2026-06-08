<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Tambah batas kuota mahasiswa bimbingan per-dosen.
     * Null = belum diatur / tanpa batas.
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->unsignedInteger('kuota_bimbingan')->nullable()->after('laboratorium_id');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('kuota_bimbingan');
        });
    }
};
