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
        'link',
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
    public static function buat($userId, $tipe, $pesan, $link = null)
    {
        return DB::table('aktivitas')->insert([
            'user_id' => $userId,
            'tipe' => $tipe,
            'pesan' => $pesan,
            'link' => $link,
            'is_read' => DB::raw('false'), // 🔥 Postgres boolean
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    /**
     * Kirim satu notifikasi yang sama ke banyak user (batch insert).
     * @param iterable $userIds
     */
    public static function buatBanyak($userIds, $tipe, $pesan, $link = null)
    {
        $now = now();
        $rows = [];
        foreach ($userIds as $uid) {
            $rows[] = [
                'user_id' => $uid,
                'tipe' => $tipe,
                'pesan' => $pesan,
                'link' => $link,
                'is_read' => DB::raw('false'),
                'created_at' => $now,
                'updated_at' => $now,
            ];
        }

        if (!empty($rows)) {
            DB::table('aktivitas')->insert($rows);
        }
    }

    /** Helper: ambil semua id user dengan role tertentu */
    public static function userIdsByRole(string $role): array
    {
        return User::where('role', $role)->pluck('id')->all();
    }
}