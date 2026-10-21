@extends('layout')

@section('judul', 'Laporan')

@section('isi')

<div class="kepala-halaman">
    <div>
        <h1>Laporan</h1>
        <p>Rekap pinjaman dan angsuran seluruh anggota.</p>
    </div>
</div>

<div class="stat-grid">
    <div class="stat">
        <p class="stat__label">Total pinjaman</p>
        <p class="stat__nilai">{{ $ringkas['pengajuan'] }}</p>
    </div>
    <div class="stat">
        <p class="stat__label">Berjalan</p>
        <p class="stat__nilai">{{ $ringkas['aktif'] }}</p>
    </div>
    <div class="stat">
        <p class="stat__label">Lunas</p>
        <p class="stat__nilai">{{ $ringkas['lunas'] }}</p>
    </div>
    <div class="stat">
        <p class="stat__label">Pokok disalurkan</p>
        <p class="stat__nilai">Rp{{ number_format($ringkas['pokok'], 0, ',', '.') }}</p>
    </div>
    <div class="stat">
        <p class="stat__label">Angsuran diterima</p>
        <p class="stat__nilai">Rp{{ number_format($ringkas['diterima'], 0, ',', '.') }}</p>
    </div>
    <div class="stat">
        <p class="stat__label">Belum tertagih</p>
        <p class="stat__nilai">Rp{{ number_format($ringkas['tertunggak'], 0, ',', '.') }}</p>
    </div>
</div>

<section class="panel">
    <div class="panel__kepala"><h2>Rekap per anggota</h2></div>
    <div class="panel__badan panel__badan--rapat">
        <div class="tabel-bungkus">
            <table class="data">
                <thead>
                    <tr>
                        <th>Anggota</th>
                        <th class="num">Jumlah pinjaman</th>
                        <th class="num">Total pokok</th>
                        <th class="num">Sisa tagihan</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($perAnggota as $row)
                        <tr>
                            <td>{{ $row['nama'] }}</td>
                            <td class="num">{{ $row['jumlah'] }}</td>
                            <td class="num">Rp{{ number_format($row['pokok'], 0, ',', '.') }}</td>
                            <td class="num">Rp{{ number_format($row['sisa'], 0, ',', '.') }}</td>
                        </tr>
                    @empty
                        <tr><td colspan="4" class="kosong">Belum ada data.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</section>

@endsection
