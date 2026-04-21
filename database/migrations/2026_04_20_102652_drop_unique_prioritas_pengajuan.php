<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    public function up(): void
    {
        DB::statement('ALTER TABLE pengajuan DROP CONSTRAINT IF EXISTS pengajuan_mahasiswa_id_prioritas_unique');
    }

    public function down(): void
    {
        DB::statement('ALTER TABLE pengajuan ADD CONSTRAINT pengajuan_mahasiswa_id_prioritas_unique UNIQUE (mahasiswa_id, prioritas)');
    }
};