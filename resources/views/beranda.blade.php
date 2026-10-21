@extends('layout')

@section('judul', 'Beranda')

@section('isi')

<section class="hero">
    <div>
        <p class="hero__label">Total pinjaman disalurkan</p>
        <p class="hero__angka">Rp{{ number_format($totalDisalurkan, 0, ',', '.') }}</p>
        <p class="hero__ket">
            Dana yang sudah dicairkan ke anggota. Semua angsuran dihitung dengan bunga flat,
            jadi angkanya tetap sama tiap bulan sampai lunas.
        </p>
        <div class="form-aksi">
            <a class="btn" href="/simulasi">Hitung angsuran</a>
            <a class="btn btn--garis" href="/pinjaman/baru">Ajukan pinjaman</a>
        </div>
    </div>
    @include('partials.ilustrasi')
</section>

<div class="stat-grid">
    <div class="stat">
        <p class="stat__label">Anggota terdaftar</p>
        <p class="stat__nilai">{{ $totalAnggota }}</p>
    </div>
    <div class="stat">
        <p class="stat__label">Pinjaman berjalan</p>
        <p class="stat__nilai">{{ $pinjamanAktif }}</p>
    </div>
    <div class="stat">
        <p class="stat__label">Angsuran diterima</p>
        <p class="stat__nilai">Rp{{ number_format($totalDiterima, 0, ',', '.') }}</p>
    </div>
</div>

<section class="panel">
    <div class="panel__kepala">
        <h2>Kalau pinjam Rp5.000.000, angsurannya segini</h2>
        <a class="btn btn--tonal btn--mini" href="/simulasi">Ubah nominal</a>
    </div>
    <div class="panel__badan">
        <p style="color: var(--color-muted); font-size: var(--text-sm); margin-bottom: var(--space-4);">
            Bunga flat 1,5% per bulan. Pilih tenor, angkanya langsung kelihatan.
        </p>
        <div class="tenor-grid tenor-grid--rapat">
            @foreach ($contoh as $c)
                <a class="tenor" href="/simulasi?pokok=5000000&bunga=1.5&tenor={{ $c['tenor'] }}">
                    <span class="tenor__bulan">{{ $c['tenor'] }} bulan</span>
                    <span class="tenor__nilai">Rp{{ number_format($c['per_bulan'], 0, ',', '.') }}</span>
                    <span class="tenor__bulan" style="margin-top: 4px;">per bulan</span>
                </a>
            @endforeach
        </div>
    </div>
</section>

<section class="panel">
    <div class="panel__kepala">
        <h2>Pinjaman terbaru</h2>
        <a class="btn btn--teks btn--mini" href="/pinjaman">Lihat semua <svg class="ikon" aria-hidden="true"><use href="#i-kanan"/></svg></a>
    </div>
    <div class="panel__badan panel__badan--rapat">
        <div class="tabel-bungkus">
            <table class="data">
                <thead>
                    <tr>
                        <th>Anggota</th>
                        <th class="num">Pokok</th>
                        <th class="num">Tenor</th>
                        <th class="num">Angsuran</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($terbaru as $p)
                        <tr>
                            <td>{{ $p->anggota->nama ?? '—' }}</td>
                            <td class="num">Rp{{ number_format($p->pokok, 0, ',', '.') }}</td>
                            <td class="num">{{ $p->tenor }} bln</td>
                            <td class="num">Rp{{ number_format($p->angsuran_bulanan, 0, ',', '.') }}</td>
                            <td><span class="lencana {{ $p->status === 'Lunas' ? 'lencana--lunas' : 'lencana--aktif' }}">{{ $p->status }}</span></td>
                        </tr>
                    @empty
                        <tr><td colspan="5" class="kosong">Belum ada pinjaman tercatat.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</section>

@endsection
