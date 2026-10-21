<?php

namespace App\Http\Controllers;

use App\Models\Angsuran;
use App\Models\Pinjaman;

class LaporanController extends Controller
{
    public function index()
    {
        $ringkas = [
            'pengajuan' => Pinjaman::count(),
            'aktif' => Pinjaman::where('status', 'Aktif')->count(),
            'lunas' => Pinjaman::where('status', 'Lunas')->count(),
            'pokok' => (int) Pinjaman::sum('pokok'),
            'diterima' => (int) Angsuran::where('status', 'Lunas')->sum('jumlah'),
            'tertunggak' => (int) Angsuran::where('status', 'Belum bayar')->sum('jumlah'),
        ];

        $perAnggota = Pinjaman::with('anggota')
            ->get()
            ->groupBy('anggota_id')
            ->map(function ($baris) {
                return [
                    'nama' => $baris->first()->anggota->nama ?? '-',
                    'jumlah' => $baris->count(),
                    'pokok' => (int) $baris->sum('pokok'),
                    'sisa' => (int) $baris->sum(fn ($p) => $p->sisa),
                ];
            })
            ->sortByDesc('pokok')
            ->values();

        return view('laporan', compact('ringkas', 'perAnggota'));
    }
}
