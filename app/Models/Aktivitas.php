<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Aktivitas extends Model
{
    protected $table = 'aktivitas';

    protected $fillable = [
        'user_id',
        'tipe',
        'pesan',
        'is_read'
    ];

    protected $casts = [
        'is_read' => 'boolean',
    ];

    /*
    |--------------------------------------------------------------------------
    | RELATION
    |--------------------------------------------------------------------------
    */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /*
    |--------------------------------------------------------------------------
    | 🔥 HELPER FINAL (POSTGRES SAFE)
    |--------------------------------------------------------------------------
    */
    public static function buat($userId, $tipe, $pesan)
    {
        return DB::table('aktivitas')->insert([
            'user_id' => $userId,
            'tipe' => $tipe,
            'pesan' => $pesan,
            'is_read' => DB::raw('false'), // 🔥 INI KUNCI UTAMA
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}