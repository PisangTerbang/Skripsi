<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('judul', function (Blueprint $table) {
            $table->id();

            $table->string('kode')->nullable();

            $table->foreignId('laboratorium_id')->constrained('laboratorium')->cascadeOnDelete();
            $table->foreignId('dosen_id')->constrained('users')->cascadeOnDelete();

            $table->string('nama_judul');
            $table->text('deskripsi')->nullable();

            $table->boolean('aktif')->default(true);
            $table->boolean('is_locked')->default(false);

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('judul');
    }
};