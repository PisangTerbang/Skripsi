<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

/**
 * Mengaktifkan Row Level Security (RLS) pada SEMUA tabel di skema public untuk
 * menutup peringatan keamanan "RLS Disabled in Public" dari Supabase Advisor.
 *
 * Aman untuk aplikasi: Laravel terhubung sebagai role `postgres` (rolbypassrls=true)
 * sehingga RLS TIDAK mempengaruhi query aplikasi. Tanpa policy permissive, role API
 * Supabase (anon/authenticated) otomatis ditolak — inilah yang menutup peringatan.
 */
return new class extends Migration
{
    public function up(): void
    {
        foreach ($this->publicTables(false) as $table) {
            try {
                DB::statement('ALTER TABLE public."' . $table . '" ENABLE ROW LEVEL SECURITY;');
            } catch (\Throwable $e) {
                // Lewati tabel yang tak dapat diubah (mis. milik sistem).
            }
        }
    }

    public function down(): void
    {
        foreach ($this->publicTables(true) as $table) {
            try {
                DB::statement('ALTER TABLE public."' . $table . '" DISABLE ROW LEVEL SECURITY;');
            } catch (\Throwable $e) {
                // abaikan
            }
        }
    }

    /**
     * Daftar tabel reguler di skema public.
     * $withRls=false → hanya yang RLS-nya belum aktif (untuk up()).
     * $withRls=true  → hanya yang RLS-nya aktif (untuk down()).
     */
    private function publicTables(bool $withRls): array
    {
        // Bandingkan boolean dengan literal (bukan parameter terikat) agar tak kena
        // error pgsql "operator does not exist: boolean = integer". $withRls dikontrol
        // kode (bukan input pengguna), jadi aman diinterpolasi.
        $literal = $withRls ? 'true' : 'false';

        $rows = DB::select(
            "select c.relname as tablename
             from pg_class c
             join pg_namespace n on n.oid = c.relnamespace
             where n.nspname = 'public' and c.relkind = 'r' and c.relrowsecurity = $literal"
        );

        return array_map(fn($r) => $r->tablename, $rows);
    }
};
