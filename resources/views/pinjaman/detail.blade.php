@extends('layout')

@section('judul', 'Detail Pinjaman')

@section('isi')

<div class="kepala-halaman">
    <div>
        <h1>Pinjaman {{ $pinjaman->anggota->nama ?? '—' }}</h1>
        <p>
            Dicairkan {{ $pinjaman->tanggal->format('d/m/Y') }} · {{ $pinjaman->keperluan }}
            · Bunga {{ rtrim(rtrim(number_format($pinjaman->bunga_persen, 2, ',', '.'), '0'), ',') }}%/bulan
            · Tenor {{ $pinjaman->tenor }} bulan
        </p>
    </div>
    <div style="display: flex; gap: var(--space-2);">
        <a class="btn btn--teks" href="/pinjaman"><svg class="ikon" aria-hidden="true"><use href="#i-kiri"/></svg> Kembali</a>
        @if ($pinjaman->status !== 'Lunas')
            <form method="POST" action="/pinjaman/{{ $pinjaman->id }}/bayar">
                @csrf
                <button class="btn" type="submit"><svg class="ikon" aria-hidden="true"><use href="#i-cek"/></svg> Terima angsuran berikutnya</button>
            </form>
        @endif
    </div>
</div>

<div class="ringkasan">
    <div class="ringkasan__kotak">
        <p class="ringkasan__label">Pokok</p>
        <p class="ringkasan__nilai">Rp{{ number_format($pinjaman->pokok, 0, ',', '.') }}</p>
    </div>
    <div class="ringkasan__kotak">
        <p class="ringkasan__label">Bunga / bulan</p>
        <p class="ringkasan__nilai">{{ rtrim(rtrim(number_format($pinjaman->bunga_persen, 2, ',', '.'), '0'), ',') }}%</p>
    </div>
    <div class="ringkasan__kotak">
        <p class="ringkasan__label">Angsuran / bulan</p>
        <p class="ringkasan__nilai">Rp{{ number_format($pinjaman->angsuran_bulanan, 0, ',', '.') }}</p>
    </div>
    <div class="ringkasan__kotak">
        <p class="ringkasan__label">Sudah dibayar</p>
        <p class="ringkasan__nilai">Rp{{ number_format($pinjaman->terbayar, 0, ',', '.') }}</p>
    </div>
    <div class="ringkasan__kotak">
        <p class="ringkasan__label">Sisa</p>
        <p class="ringkasan__nilai">Rp{{ number_format($pinjaman->sisa, 0, ',', '.') }}</p>
    </div>
</div>

<section class="panel">
    <div class="panel__kepala">
        <h2>Jadwal angsuran</h2>
        <span class="lencana {{ $pinjaman->status === 'Lunas' ? 'lencana--lunas' : 'lencana--aktif' }}">
            {{ $pinjaman->status }} · {{ $pinjaman->progres }}%
        </span>
    </div>
    <div class="panel__badan panel__badan--rapat">
        <div class="tabel-bungkus">
            <table class="data">
                <thead>
                    <tr>
                        <th class="num">Ke</th>
                        <th>Jatuh tempo</th>
                        <th class="hp-sembunyi">Tanggal bayar</th>
                        <th class="num">Jumlah</th>
                        <th>Status</th>
                        <th class="num kolom-aksi">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @php $sudahAdaTombol = false; @endphp
                    @foreach ($pinjaman->angsuran as $a)
                        <tr>
                            <td class="num">{{ $a->angsuran_ke }}</td>
                            <td>{{ $a->jatuh_tempo->format('d/m/Y') }}</td>
                            <td class="hp-sembunyi">{{ $a->tanggal_bayar ? $a->tanggal_bayar->format('d/m/Y') : '—' }}</td>
                            <td class="num">Rp{{ number_format($a->jumlah, 0, ',', '.') }}</td>
                            <td>
                                <span class="lencana {{ $a->status === 'Lunas' ? 'lencana--lunas' : 'lencana--belum' }}">{{ $a->status }}</span>
                            </td>
                            <td class="num kolom-aksi">
                                @if ($a->status !== 'Lunas' && ! $sudahAdaTombol)
                                    @php $sudahAdaTombol = true; @endphp
                                    <form method="POST" action="/pinjaman/{{ $pinjaman->id }}/bayar">
                                        @csrf
                                        <button type="submit" class="btn btn--mini"><svg class="ikon" aria-hidden="true"><use href="#i-cek"/></svg> Bayar</button>
                                    </form>
                                @else
                                    <span class="redup">—</span>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</section>

<section class="panel">
    <div class="panel__kepala"><h2>Data anggota</h2></div>
    <div class="panel__badan">
        <div class="form-grid">
            <div>
                <p class="ringkasan__label">Nama</p>
                <p>{{ $pinjaman->anggota->nama ?? '—' }}</p>
            </div>
            <div>
                <p class="ringkasan__label">NIK</p>
                <p>{{ $pinjaman->anggota->nik ?? '—' }}</p>
            </div>
            <div>
                <p class="ringkasan__label">No. HP</p>
                <p>{{ $pinjaman->anggota->no_hp ?? '—' }}</p>
            </div>
            <div>
                <p class="ringkasan__label">Pekerjaan</p>
                <p>{{ $pinjaman->anggota->pekerjaan ?? '—' }}</p>
            </div>
            <div>
                <p class="ringkasan__label">Alamat</p>
                <p>{{ $pinjaman->anggota->alamat ?? '—' }}</p>
            </div>
            <div>
                <p class="ringkasan__label">Penghasilan</p>
                <p>Rp{{ number_format($pinjaman->anggota->penghasilan ?? 0, 0, ',', '.') }}</p>
            </div>
        </div>
    </div>
</section>

@endsection
