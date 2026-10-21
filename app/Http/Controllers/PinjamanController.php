<?php

namespace App\Http\Controllers;

use App\Models\Anggota;
use App\Models\Pinjaman;
use App\Support\Kalkulator;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class PinjamanController extends Controller
{
    public function index(Request $request)
    {
        $status = $request->query('status');

        $daftar = Pinjaman::with('anggota')
            ->when($status, fn ($q) => $q->where('status', $status))
            ->latest('tanggal')
            ->paginate(10)
            ->withQueryString();

        return view('pinjaman.index', compact('daftar', 'status'));
    }

    public function create()
    {
        $anggota = Anggota::orderBy('nama')->get();

        return view('pinjaman.form', compact('anggota'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'anggota_id' => ['required', 'exists:anggota,id'],
            'pokok' => ['required', 'numeric', 'min:500000', 'max:50000000'],
            'tenor' => ['required', 'integer', 'in:3,6,12,18,24'],
            'bunga_persen' => ['required', 'numeric', 'min:0', 'max:5'],
            'keperluan' => ['required', 'string', 'max:80'],
            'tanggal' => ['required', 'date'],
        ]);

        $hitung = Kalkulator::hitung((int) $data['pokok'], (int) $data['tenor'], (float) $data['bunga_persen']);

        $pinjaman = Pinjaman::create([
            'anggota_id' => $data['anggota_id'],
            'pokok' => (int) $data['pokok'],
            'tenor' => (int) $data['tenor'],
            'bunga_persen' => $data['bunga_persen'],
            'keperluan' => $data['keperluan'],
            'status' => 'Aktif',
            'tanggal' => $data['tanggal'],
        ]);

        $this->buatJadwal($pinjaman, $hitung);

        return redirect('/pinjaman/' . $pinjaman->id)->with('sukses', 'Pinjaman disetujui, jadwal angsuran dibuat.');
    }

    public function show(Pinjaman $pinjaman)
    {
        $pinjaman->load('anggota', 'angsuran');

        return view('pinjaman.detail', compact('pinjaman'));
    }

    public function bayar(Pinjaman $pinjaman)
    {
        $berikut = $pinjaman->angsuran()->where('status', 'Belum bayar')->orderBy('angsuran_ke')->first();

        if (! $berikut) {
            return back()->with('sukses', 'Semua angsuran sudah lunas.');
        }

        $berikut->update([
            'status' => 'Lunas',
            'tanggal_bayar' => now()->toDateString(),
        ]);

        if (! $pinjaman->angsuran()->where('status', 'Belum bayar')->exists()) {
            $pinjaman->update(['status' => 'Lunas']);
        }

        return back()->with('sukses', "Angsuran ke-{$berikut->angsuran_ke} diterima.");
    }

    public function destroy(Pinjaman $pinjaman)
    {
        $pinjaman->delete();

        return redirect('/pinjaman')->with('sukses', 'Pinjaman dibatalkan.');
    }

    private function buatJadwal(Pinjaman $pinjaman, array $hitung): void
    {
        $mulai = Carbon::parse($pinjaman->tanggal);
        $sisa = $hitung['total_bayar'];

        for ($i = 1; $i <= $pinjaman->tenor; $i++) {
            $jumlah = ($i === $pinjaman->tenor) ? $sisa : $hitung['per_bulan'];
            $sisa -= $jumlah;

            $pinjaman->angsuran()->create([
                'angsuran_ke' => $i,
                'jatuh_tempo' => $mulai->copy()->addMonths($i)->toDateString(),
                'jumlah' => $jumlah,
                'status' => 'Belum bayar',
            ]);
        }
    }
}
