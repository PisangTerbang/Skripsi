<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('pengajuan', function (Blueprint $table) {
            $table->id();

            // Mahasiswa yang mengajukan
            $table->foreignId('mahasiswa_id')->constrained('users')->cascadeOnDelete();

            // Jika memilih judul dosen
            $table->foreignId('judul_id')->nullable()->constrained('judul')->nullOnDelete();

            // Jika judul mandiri
            $table->string('judul_mandiri')->nullable();
            $table->text('deskripsi_mandiri')->nullable();
            $table->foreignId('dosen_pilihan_id')->nullable()->constrained('users')->nullOnDelete();

            // Jenis pengajuan
            $table->enum('jenis', ['pilih', 'mandiri']);

            // Prioritas pilihan (1,2,3)
            $table->integer('prioritas')->nullable();

            // Alasan memilih
            $table->text('alasan')->nullable();

            // Status keputusan dosen
            $table->enum('status', ['pending', 'disetujui', 'ditolak'])->default('pending');

            // Catatan dosen
            $table->text('catatan_dosen')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pengajuan');
    }
};
