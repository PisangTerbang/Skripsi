<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Update nama lab SS menjadi SISTEM SIBER
        DB::table('laboratorium')
            ->where('nama', 'SS')
            ->update(['nama' => 'SISTEM SIBER']);
    }

    public function down(): void
    {
        DB::table('laboratorium')
            ->where('nama', 'SISTEM SIBER')
            ->update(['nama' => 'SS']);
    }
};
