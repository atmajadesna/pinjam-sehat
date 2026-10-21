<?php

namespace App\Http\Controllers;

use App\Models\Anggota;
use App\Models\Angsuran;
use App\Models\Pinjaman;
use App\Support\Kalkulator;

class BerandaController extends Controller
{
    public function index()
    {
        $totalAnggota = Anggota::count();
        $pinjamanAktif = Pinjaman::whereIn('status', ['Aktif', 'Berjalan'])->count();
        $totalDisalurkan = (int) Pinjaman::whereIn('status', ['Aktif', 'Berjalan', 'Lunas'])->sum('pokok');
        $totalDiterima = (int) Angsuran::where('status', 'Lunas')->sum('jumlah');
        $terbaru = Pinjaman::with('anggota')->latest('tanggal')->limit(5)->get();

        $contoh = Kalkulator::bandingkan(5_000_000, 1.5, [3, 6, 12, 24]);

        return view('beranda', compact(
            'totalAnggota', 'pinjamanAktif', 'totalDisalurkan', 'totalDiterima', 'terbaru', 'contoh'
        ));
    }
}
