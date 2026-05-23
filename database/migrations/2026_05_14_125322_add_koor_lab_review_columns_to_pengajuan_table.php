<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('pengajuan', function (Blueprint $table) {
            $table->timestamp('tanggal_review_koor')->nullable()->after('catatan_koor_pengajuan');
            $table->foreignId('reviewed_by_koor')->nullable()->after('tanggal_review_koor')
                ->constrained('users')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('pengajuan', function (Blueprint $table) {
            $table->dropForeign(['reviewed_by_koor']);
            $table->dropColumn(['tanggal_review_koor', 'reviewed_by_koor']);
        });
    }
};
