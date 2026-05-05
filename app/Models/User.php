<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use App\Models\Pengajuan;
use App\Models\Judul;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'nim',
        'avatar',
        'laboratorium_id'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function pengajuanMahasiswa()
    {
        return $this->hasMany(Pengajuan::class, 'mahasiswa_id');
    }

    public function judulDosen()
    {
        return $this->hasMany(Judul::class, 'dosen_id');
    }

    public function aktivitas()
    {
        return $this->hasMany(Aktivitas::class);
    }

    public function isMahasiswa()
    {
        return $this->role === 'mahasiswa';
    }

    public function isDosen()
    {
        return $this->role === 'dosen';
    }

    public function laboratorium()
    {
        return $this->belongsTo(Laboratorium::class);
    }

    public function isKoorLab()
    {
        return $this->role === 'koor_lab';
    }

    public function isKepalaLab()
    {
        return $this->role === 'kepala_lab';
    }

    public function isKaprodi()
    {
        return $this->role === 'kaprodi';
    }

}
