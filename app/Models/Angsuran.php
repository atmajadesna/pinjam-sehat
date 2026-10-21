<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Angsuran extends Model
{
    protected $table = 'angsuran';

    protected $fillable = [
        'pinjaman_id', 'angsuran_ke', 'jatuh_tempo', 'tanggal_bayar', 'jumlah', 'denda', 'status',
    ];

    protected $casts = [
        'jatuh_tempo' => 'date',
        'tanggal_bayar' => 'date',
    ];

    /** Berapa hari lewat jatuh tempo (0 kalau belum lewat atau sudah lunas). */
    public function getHariTelatAttribute(): int
    {
        if ($this->status === 'Lunas' || ! $this->jatuh_tempo) {
            return 0;
        }

        return max(0, (int) $this->jatuh_tempo->copy()->startOfDay()->diffInDays(now()->startOfDay(), false));
    }

    /** Denda berjalan sesuai aturan 0,1% per hari. */
    public function getDendaTerhitungAttribute(): int
    {
        return \App\Support\Kalkulator::denda((int) $this->jumlah, $this->hari_telat);
    }

    public function pinjaman()
    {
        return $this->belongsTo(Pinjaman::class, 'pinjaman_id');
    }
}
