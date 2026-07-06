<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Tandai apakah sebuah pengumuman menampilkan tabel "Hasil Penetapan Judul TA".
     * Default true agar pengumuman lama (hasil pengajuan) tetap seperti semula;
     * pengumuman info umum bisa mematikannya.
     */
    public function up(): void
    {
        Schema::table('pengumuman', function (Blueprint $table) {
            $table->boolean('tampilkan_hasil')->default(true)->after('isi');
        });
    }

    public function down(): void
    {
        Schema::table('pengumuman', function (Blueprint $table) {
            $table->dropColumn('tampilkan_hasil');
        });
    }
};
