<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Laboratorium extends Model
{
    protected $table = 'laboratorium';

    protected $fillable = [
        'nama',
        'deskripsi'
    ];

    public function judul()
    {
        return $this->hasMany(Judul::class);
    }
}
