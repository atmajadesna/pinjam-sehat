<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pinjaman extends Model
{
    protected $table = 'pinjaman';

    protected $fillable = [
        'anggota_id', 'pokok', 'tenor', 'bunga_persen', 'keperluan', 'status', 'tanggal',
    ];

    protected $casts = [
        'tanggal' => 'date',
        'bunga_persen' => 'float',
    ];

    public function anggota()
    {
        return $this->belongsTo(Anggota::class, 'anggota_id');
    }

    public function angsuran()
    {
        return $this->hasMany(Angsuran::class, 'pinjaman_id')->orderBy('angsuran_ke');
    }

    /** Bunga total (bunga flat per bulan). */
    public function getTotalBungaAttribute(): int
    {
        return (int) round($this->pokok * ($this->bunga_persen / 100) * $this->tenor);
    }

    /** Pokok + bunga. */
    public function getTotalBayarAttribute(): int
    {
        return (int) $this->pokok + $this->total_bunga;
    }

    /** Angsuran tiap bulan. */
    public function getAngsuranBulananAttribute(): int
    {
        return (int) round($this->total_bayar / max(1, $this->tenor));
    }

    public function getTerbayarAttribute(): int
    {
        return (int) $this->angsuran()->where('status', 'Lunas')->sum('jumlah');
    }

    public function getSisaAttribute(): int
    {
        return max(0, $this->total_bayar - $this->terbayar);
    }

    public function getProgresAttribute(): int
    {
        return $this->total_bayar > 0
            ? (int) round($this->terbayar / $this->total_bayar * 100)
            : 0;
    }
}
