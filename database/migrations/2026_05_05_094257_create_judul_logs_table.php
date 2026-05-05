<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('judul_logs', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('judul_id');
            $table->unsignedBigInteger('user_id'); // siapa yang melakukan aksi
            $table->string('aksi'); // diajukan, dikelompokkan, divalidasi, ditolak, dll
            $table->string('dari_status')->nullable();
            $table->string('ke_status')->nullable();
            $table->text('catatan')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('judul_logs');
    }
};
