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
        Schema::table('judul', function (Blueprint $table) {
            // Kuota maksimal (opsional, bisa null = unlimited)
            $table->integer('kuota_maksimal')->nullable()->after('is_active');

            // Counter otomatis (akan di-update via observer/event)
            $table->integer('jumlah_peminat')->default(0)->after('kuota_maksimal');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('judul', function (Blueprint $table) {
            $table->dropColumn([
                'kuota_maksimal',
                'jumlah_peminat',
            ]);
        });
    }
};
