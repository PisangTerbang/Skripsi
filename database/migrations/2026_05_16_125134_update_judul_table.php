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
        Schema::table('judul', function (Blueprint $table) {
            // Hapus kolom approval bertingkat (workflow lama)
            // Cek dulu apakah kolom ada sebelum drop (untuk safety)
            if (Schema::hasColumn('judul', 'status_kalab')) {
                $table->dropColumn('status_kalab');
            }
            if (Schema::hasColumn('judul', 'status_koorlab')) {
                $table->dropColumn('status_koorlab');
            }
            if (Schema::hasColumn('judul', 'status_kaprodi')) {
                $table->dropColumn('status_kaprodi');
            }
            if (Schema::hasColumn('judul', 'approved_by_kalab')) {
                $table->dropColumn('approved_by_kalab');
            }
            if (Schema::hasColumn('judul', 'approved_by_koorlab')) {
                $table->dropColumn('approved_by_koorlab');
            }
            if (Schema::hasColumn('judul', 'approved_by_kaprodi')) {
                $table->dropColumn('approved_by_kaprodi');
            }
            if (Schema::hasColumn('judul', 'kalab_notes')) {
                $table->dropColumn('kalab_notes');
            }
            if (Schema::hasColumn('judul', 'koorlab_notes')) {
                $table->dropColumn('koorlab_notes');
            }
            if (Schema::hasColumn('judul', 'kaprodi_notes')) {
                $table->dropColumn('kaprodi_notes');
            }
        });

        // Tambah kolom baru menggunakan raw SQL untuk PostgreSQL compatibility
        DB::statement("
            ALTER TABLE judul 
            ADD COLUMN status VARCHAR(20) DEFAULT 'available' CHECK (status IN ('draft', 'available', 'inactive'))
        ");

        DB::statement("
            COMMENT ON COLUMN judul.status IS 'Status judul: draft=belum siap, available=tersedia untuk mahasiswa, inactive=tidak aktif'
        ");

        DB::statement("
            ALTER TABLE judul 
            ADD COLUMN is_available BOOLEAN DEFAULT true
        ");

        DB::statement("
            COMMENT ON COLUMN judul.is_available IS 'Apakah judul tersedia untuk dipilih mahasiswa'
        ");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Hapus kolom baru
        Schema::table('judul', function (Blueprint $table) {
            if (Schema::hasColumn('judul', 'status')) {
                $table->dropColumn('status');
            }
            if (Schema::hasColumn('judul', 'is_available')) {
                $table->dropColumn('is_available');
            }
        });

        // Kembalikan kolom approval bertingkat
        Schema::table('judul', function (Blueprint $table) {
            $table->string('status_kalab', 20)->default('pending');
            $table->string('status_koorlab', 20)->default('pending');
            $table->string('status_kaprodi', 20)->default('pending');

            $table->bigInteger('approved_by_kalab')->nullable();
            $table->bigInteger('approved_by_koorlab')->nullable();
            $table->bigInteger('approved_by_kaprodi')->nullable();

            $table->text('kalab_notes')->nullable();
            $table->text('koorlab_notes')->nullable();
            $table->text('kaprodi_notes')->nullable();
        });

        // Tambah foreign key constraints
        DB::statement("
            ALTER TABLE judul 
            ADD CONSTRAINT judul_approved_by_kalab_foreign 
            FOREIGN KEY (approved_by_kalab) REFERENCES users(id) ON DELETE SET NULL
        ");

        DB::statement("
            ALTER TABLE judul 
            ADD CONSTRAINT judul_approved_by_koorlab_foreign 
            FOREIGN KEY (approved_by_koorlab) REFERENCES users(id) ON DELETE SET NULL
        ");

        DB::statement("
            ALTER TABLE judul 
            ADD CONSTRAINT judul_approved_by_kaprodi_foreign 
            FOREIGN KEY (approved_by_kaprodi) REFERENCES users(id) ON DELETE SET NULL
        ");
    }
};
