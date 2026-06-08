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
        'laboratorium_id',
        'kuota_bimbingan',
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
            'kuota_bimbingan' => 'integer',
        ];
    }

    /**
     * Jumlah mahasiswa bimbingan saat ini (judul final yang ditetapkan & disetujui
     * untuk salah satu judul milik dosen ini).
     */
    public function jumlahBimbingan(): int
    {
        return Pengajuan::where('status', 'disetujui')
            ->whereHas('judulDitetapkan', function ($q) {
                $q->where('dosen_id', $this->id);
            })
            ->count();
    }

    /**
     * Sisa kuota bimbingan. Null jika kuota belum diatur (tanpa batas).
     */
    public function sisaKuotaBimbingan(): ?int
    {
        if (is_null($this->kuota_bimbingan)) {
            return null;
        }

        return max(0, $this->kuota_bimbingan - $this->jumlahBimbingan());
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

    public function isKaLab()
    {
        return $this->role === 'ka_lab';
    }

    public function isProdi()
    {
        return $this->role === 'prodi';
    }

    public function isKoordinatorTa()
    {
        return $this->role === 'koordinator_ta';
    }

}
