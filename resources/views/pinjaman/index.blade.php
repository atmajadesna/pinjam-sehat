@extends('layout')

@section('judul', 'Pinjaman')

@section('isi')

<div class="kepala-halaman">
    <div>
        <h1>Pinjaman</h1>
        <p>Semua pengajuan dan pinjaman berjalan.</p>
    </div>
    <a class="btn" href="/pinjaman/baru"><svg class="ikon" aria-hidden="true"><use href="#i-plus"/></svg> Pinjaman baru</a>
</div>

<section class="panel">
    <div class="panel__kepala">
        <div class="segmen" role="group" aria-label="Saring status pinjaman">
            <a class="segmen__item {{ $status ? '' : 'is-aktif' }}" href="/pinjaman">Semua</a>
            <a class="segmen__item {{ $status === 'Aktif' ? 'is-aktif' : '' }}" href="/pinjaman?status=Aktif">Berjalan</a>
            <a class="segmen__item {{ $status === 'Lunas' ? 'is-aktif' : '' }}" href="/pinjaman?status=Lunas">Lunas</a>
        </div>
    </div>
    <div class="panel__badan panel__badan--rapat">
        <div class="tabel-bungkus">
            <table class="data">
                <thead>
                    <tr>
                        <th class="hp-sembunyi">Tanggal</th>
                        <th>Anggota</th>
                        <th class="num">Pokok</th>
                        <th class="num hp-sembunyi">Tenor</th>
                        <th class="num">Angsuran</th>
                        <th class="num hp-sembunyi">Sisa</th>
                        <th>Status</th>
                        <th class="kolom-aksi"></th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($daftar as $p)
                        <tr>
                            <td class="num hp-sembunyi" style="text-align: left;">{{ $p->tanggal->format('d/m/Y') }}</td>
                            <td>{{ $p->anggota->nama ?? '—' }}</td>
                            <td class="num">Rp{{ number_format($p->pokok, 0, ',', '.') }}</td>
                            <td class="num hp-sembunyi">{{ $p->tenor }} bln</td>
                            <td class="num">Rp{{ number_format($p->angsuran_bulanan, 0, ',', '.') }}</td>
                            <td class="num hp-sembunyi">Rp{{ number_format($p->sisa, 0, ',', '.') }}</td>
                            <td>
                                <span class="lencana {{ $p->status === 'Lunas' ? 'lencana--lunas' : 'lencana--aktif' }}">{{ $p->status }}</span>
                            </td>
                            <td class="kolom-aksi"><a class="btn btn--teks btn--mini" href="/pinjaman/{{ $p->id }}">Detail <svg class="ikon" aria-hidden="true"><use href="#i-kanan"/></svg></a></td>
                        </tr>
                    @empty
                        <tr><td colspan="8" class="kosong">Belum ada pinjaman.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="paginasi">{{ $daftar->links() }}</div>
    </div>
</section>

@endsection
