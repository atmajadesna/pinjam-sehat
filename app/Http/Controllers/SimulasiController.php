<?php

namespace App\Http\Controllers;

use App\Support\Kalkulator;
use Illuminate\Http\Request;

class SimulasiController extends Controller
{
    public function index(Request $request)
    {
        $pokok = (int) $request->query('pokok', 5_000_000);
        $bunga = (float) $request->query('bunga', Kalkulator::BUNGA_DEFAULT);

        $pokok = max(500_000, min(50_000_000, $pokok));
        $bunga = max(Kalkulator::BUNGA_MIN, min(Kalkulator::BUNGA_MAKS, $bunga));

        $tenor = [3, 6, 12, 18, 24];
        $hasil = Kalkulator::bandingkan($pokok, $bunga, $tenor);
        $dipilih = (int) $request->query('tenor', 12);

        $rincian = null;
        if (in_array($dipilih, $tenor, true)) {
            $rincian = Kalkulator::hitung($pokok, $dipilih, $bunga);
        }

        return view('simulasi', compact('pokok', 'bunga', 'tenor', 'hasil', 'dipilih', 'rincian'));
    }
}
