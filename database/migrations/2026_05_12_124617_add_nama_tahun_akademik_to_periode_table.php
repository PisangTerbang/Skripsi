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
        Schema::table('periode', function (Blueprint $table) {
            if (!Schema::hasColumn('periode', 'nama')) {
                $table->string('nama')->nullable()->after('id');
            }

            if (!Schema::hasColumn('periode', 'tahun_akademik')) {
                $table->string('tahun_akademik')->nullable()->after('nama');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('periode', function (Blueprint $table) {
            if (Schema::hasColumn('periode', 'nama')) {
                $table->dropColumn('nama');
            }

            if (Schema::hasColumn('periode', 'tahun_akademik')) {
                $table->dropColumn('tahun_akademik');
            }
        });
    }
};
