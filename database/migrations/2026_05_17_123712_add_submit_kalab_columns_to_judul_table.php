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
            // Kolom untuk tracking submit ke Kepala Lab
            $table->timestamp('submitted_to_kalab_at')->nullable()->after('reviewed_at_kalab');
            $table->foreignId('submitted_to_kalab_by')->nullable()->constrained('users')->onDelete('set null')->after('submitted_to_kalab_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('judul', function (Blueprint $table) {
            $table->dropForeign(['submitted_to_kalab_by']);
            $table->dropColumn(['submitted_to_kalab_at', 'submitted_to_kalab_by']);
        });
    }
};
