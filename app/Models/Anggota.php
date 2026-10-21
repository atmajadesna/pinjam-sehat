<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Anggota extends Model
{
    protected $table = 'anggota';

    protected $fillable = ['nama', 'nik', 'no_hp', 'alamat', 'pekerjaan', 'penghasilan'];

    public function pinjaman()
    {
        return $this->hasMany(Pinjaman::class, 'anggota_id');
    }
}
