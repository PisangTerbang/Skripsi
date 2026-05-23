<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // 1. Drop constraint lama (jika ada)
        DB::statement("ALTER TABLE judul DROP CONSTRAINT IF EXISTS judul_status_check");

        // 2. Tambah constraint baru dengan 'pending_kalab'
        DB::statement("
            ALTER TABLE judul 
            ADD CONSTRAINT judul_status_check 
            CHECK (status IN ('draft', 'pending_kalab', 'available', 'inactive'))
        ");

        // 3. Tambah kolom review Kalab (CEK DULU)
        Schema::table('judul', function (Blueprint $table) {
            // Cek apakah kolom sudah ada
            if (!Schema::hasColumn('judul', 'catatan_kalab')) {
                $table->text('catatan_kalab')->nullable()->after('deskripsi');
            }

            if (!Schema::hasColumn('judul', 'reviewed_by_kalab')) {
                $table->foreignId('reviewed_by_kalab')->nullable()->constrained('users')->onDelete('set null')->after('catatan_kalab');
            }

            if (!Schema::hasColumn('judul', 'reviewed_at_kalab')) {
                $table->timestamp('reviewed_at_kalab')->nullable()->after('reviewed_by_kalab');
            }
        });

        // 4. Update comment
        DB::statement("
            COMMENT ON COLUMN judul.status IS 'Status judul: draft=belum siap, pending_kalab=menunggu validasi Kepala Lab, available=tersedia untuk mahasiswa, inactive=tidak aktif'
        ");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Hapus kolom review Kalab (CEK DULU)
        Schema::table('judul', function (Blueprint $table) {
            if (Schema::hasColumn('judul', 'reviewed_by_kalab')) {
                $table->dropForeign(['reviewed_by_kalab']);
            }

            if (Schema::hasColumn('judul', 'catatan_kalab')) {
                $table->dropColumn('catatan_kalab');
            }

            if (Schema::hasColumn('judul', 'reviewed_by_kalab')) {
                $table->dropColumn('reviewed_by_kalab');
            }

            if (Schema::hasColumn('judul', 'reviewed_at_kalab')) {
                $table->dropColumn('reviewed_at_kalab');
            }
        });

        // Kembalikan constraint lama
        DB::statement("ALTER TABLE judul DROP CONSTRAINT IF EXISTS judul_status_check");
        DB::statement("
            ALTER TABLE judul 
            ADD CONSTRAINT judul_status_check 
            CHECK (status IN ('draft', 'available', 'inactive'))
        ");
    }
};
