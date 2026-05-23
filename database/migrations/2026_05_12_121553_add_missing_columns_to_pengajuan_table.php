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
        Schema::table('pengajuan', function (Blueprint $table) {
            // 3 Pilihan alternatif dari daftar judul dosen
            if (!Schema::hasColumn('pengajuan', 'pilihan_1_id')) {
                $table->foreignId('pilihan_1_id')->nullable()->after('deskripsi_mandiri')->constrained('judul')->nullOnDelete();
            }

            if (!Schema::hasColumn('pengajuan', 'pilihan_2_id')) {
                $table->foreignId('pilihan_2_id')->nullable()->after('pilihan_1_id')->constrained('judul')->nullOnDelete();
            }

            if (!Schema::hasColumn('pengajuan', 'pilihan_3_id')) {
                $table->foreignId('pilihan_3_id')->nullable()->after('pilihan_2_id')->constrained('judul')->nullOnDelete();
            }

            // Judul yang akhirnya ditetapkan
            if (!Schema::hasColumn('pengajuan', 'judul_ditetapkan_id')) {
                $table->foreignId('judul_ditetapkan_id')->nullable()->after('pilihan_3_id')->constrained('judul')->nullOnDelete();
            }

            if (!Schema::hasColumn('pengajuan', 'sumber_judul')) {
                $table->enum('sumber_judul', ['usulan', 'pilihan_1', 'pilihan_2', 'pilihan_3'])->nullable()->after('judul_ditetapkan_id');
            }

            // Tambahan untuk approval Ka Lab
            if (!Schema::hasColumn('pengajuan', 'tanggal_review_kalab')) {
                $table->timestamp('tanggal_review_kalab')->nullable()->after('catatan_kalab_pengajuan');
            }

            if (!Schema::hasColumn('pengajuan', 'reviewed_by_kalab')) {
                $table->foreignId('reviewed_by_kalab')->nullable()->after('tanggal_review_kalab')->constrained('users')->nullOnDelete();
            }

            // Tambahan untuk approval Kaprodi
            if (!Schema::hasColumn('pengajuan', 'tanggal_review_kaprodi')) {
                $table->timestamp('tanggal_review_kaprodi')->nullable()->after('catatan_kaprodi');
            }

            if (!Schema::hasColumn('pengajuan', 'reviewed_by_kaprodi')) {
                $table->foreignId('reviewed_by_kaprodi')->nullable()->after('tanggal_review_kaprodi')->constrained('users')->nullOnDelete();
            }

            // Tracking TA (untuk monitoring Kaprodi)
            if (!Schema::hasColumn('pengajuan', 'tanggal_mulai')) {
                $table->date('tanggal_mulai')->nullable()->after('reviewed_by_kaprodi');
            }

            if (!Schema::hasColumn('pengajuan', 'tanggal_selesai')) {
                $table->date('tanggal_selesai')->nullable()->after('tanggal_mulai');
            }

            if (!Schema::hasColumn('pengajuan', 'status_ta')) {
                $table->enum('status_ta', ['belum_mulai', 'berjalan', 'selesai'])->default('belum_mulai')->after('tanggal_selesai');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pengajuan', function (Blueprint $table) {
            // Drop foreign keys first
            if (Schema::hasColumn('pengajuan', 'pilihan_1_id')) {
                $table->dropForeign(['pilihan_1_id']);
                $table->dropColumn('pilihan_1_id');
            }

            if (Schema::hasColumn('pengajuan', 'pilihan_2_id')) {
                $table->dropForeign(['pilihan_2_id']);
                $table->dropColumn('pilihan_2_id');
            }

            if (Schema::hasColumn('pengajuan', 'pilihan_3_id')) {
                $table->dropForeign(['pilihan_3_id']);
                $table->dropColumn('pilihan_3_id');
            }

            if (Schema::hasColumn('pengajuan', 'judul_ditetapkan_id')) {
                $table->dropForeign(['judul_ditetapkan_id']);
                $table->dropColumn('judul_ditetapkan_id');
            }

            if (Schema::hasColumn('pengajuan', 'reviewed_by_kalab')) {
                $table->dropForeign(['reviewed_by_kalab']);
                $table->dropColumn('reviewed_by_kalab');
            }

            if (Schema::hasColumn('pengajuan', 'reviewed_by_kaprodi')) {
                $table->dropForeign(['reviewed_by_kaprodi']);
                $table->dropColumn('reviewed_by_kaprodi');
            }

            // Drop columns
            $columns = [
                'sumber_judul',
                'tanggal_review_kalab',
                'tanggal_review_kaprodi',
                'tanggal_mulai',
                'tanggal_selesai',
                'status_ta',
            ];

            foreach ($columns as $column) {
                if (Schema::hasColumn('pengajuan', $column)) {
                    $table->dropColumn($column);
                }
            }
        });
    }
};
