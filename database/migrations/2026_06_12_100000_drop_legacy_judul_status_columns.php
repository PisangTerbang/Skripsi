<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

/**
 * Kolom legacy pada tabel judul dihapus:
 * - status ('available') & is_available  -> digantikan sepenuhnya oleh status_judul
 *   (draft | pending_kalab | ditawarkan | ditolak_kalab) dan is_locked.
 * Keduanya hanya ditulis, tidak pernah dibaca untuk logika.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('judul', function (Blueprint $table) {
            if (Schema::hasColumn('judul', 'status')) {
                $table->dropColumn('status');
            }
            if (Schema::hasColumn('judul', 'is_available')) {
                $table->dropColumn('is_available');
            }
        });
    }

    public function down(): void
    {
        Schema::table('judul', function (Blueprint $table) {
            if (!Schema::hasColumn('judul', 'status')) {
                $table->string('status')->default('available');
            }
            if (!Schema::hasColumn('judul', 'is_available')) {
                $table->boolean('is_available')->default(true);
            }
        });

        // Selaraskan kembali nilai legacy dengan status_judul (best-effort).
        DB::table('judul')->whereRaw('1 = 1')->update([
            'is_available' => DB::raw("status_judul = 'ditawarkan'"),
            'status' => DB::raw("CASE WHEN status_judul = 'ditawarkan' THEN 'available' ELSE 'unavailable' END"),
        ]);
    }
};
