<?php

namespace App\Support;

class Kalkulator
{
    /**
     * Bunga flat per bulan: bunga dihitung dari pokok saja,
     * tidak menurun walau sisa pinjaman makin kecil.
     */
    public static function hitung(int $pokok, int $tenor, float $bungaPersen): array
    {
        $tenor = max(1, $tenor);
        $bungaTotal = (int) round($pokok * ($bungaPersen / 100) * $tenor);
        $totalBayar = $pokok + $bungaTotal;

        return [
            'pokok' => $pokok,
            'tenor' => $tenor,
            'bunga_persen' => $bungaPersen,
            'bunga_total' => $bungaTotal,
            'total_bayar' => $totalBayar,
            'per_bulan' => (int) round($totalBayar / $tenor),
            'bunga_per_bulan' => (int) round($bungaTotal / $tenor),
        ];
    }

    /** Simulasi untuk beberapa tenor sekaligus — dipakai halaman kalkulator. */
    public static function bandingkan(int $pokok, float $bungaPersen, array $daftarTenor): array
    {
        $hasil = [];
        foreach ($daftarTenor as $tenor) {
            $hasil[] = self::hitung($pokok, (int) $tenor, $bungaPersen);
        }

        return $hasil;
    }

    public static function rupiah($angka): string
    {
        return 'Rp' . number_format((int) $angka, 0, ',', '.');
    }
}
