<?php

namespace App\Support;

class Kalkulator
{
    /**
     * Batas yang dipakai mengikuti aturan OJK untuk pinjaman online:
     * manfaat ekonomi maksimum 0,1% per hari, setara ~3% per bulan.
     */
    public const BUNGA_MIN = 0.5;
    public const BUNGA_MAKS = 3.0;
    public const BUNGA_DEFAULT = 2.0;
    public const DENDA_PER_HARI = 0.1; // persen dari jumlah angsuran

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

    /** Bunga flat 2%/bulan kalau dinyatakan per hari. */
    public static function perHari(float $bungaPersen): float
    {
        return round($bungaPersen / 30, 3);
    }

    /** Bunga flat kalau dinyatakan per tahun. */
    public static function perTahunFlat(float $bungaPersen): float
    {
        return round($bungaPersen * 12, 1);
    }

    /**
     * Bunga efektif tahunan (IRR) — angka yang dipakai OJK untuk membandingkan
     * penawaran. Selalu lebih tinggi dari bunga flat karena pokok menurun.
     */
    public static function efektifTahunan(int $pokok, int $tenor, float $bungaPersen): float
    {
        $angsuran = self::hitung($pokok, $tenor, $bungaPersen)['per_bulan'];
        $bawah = 0.0;
        $atas = 1.0;

        for ($i = 0; $i < 80; $i++) {
            $tengah = ($bawah + $atas) / 2;
            $pv = 0.0;
            for ($b = 1; $b <= $tenor; $b++) {
                $pv += $angsuran / ((1 + $tengah) ** $b);
            }
            if ($pv > $pokok) {
                $bawah = $tengah;
            } else {
                $atas = $tengah;
            }
        }

        return round((($bawah + $atas) / 2) * 12 * 100, 1);
    }

    /** Denda keterlambatan 0,1% per hari dari jumlah angsuran. */
    public static function denda(int $jumlah, int $hariTelat): int
    {
        return $hariTelat > 0 ? (int) round($jumlah * (self::DENDA_PER_HARI / 100) * $hariTelat) : 0;
    }

    public static function rupiah($angka): string
    {
        return 'Rp' . number_format((int) $angka, 0, ',', '.');
    }
}
