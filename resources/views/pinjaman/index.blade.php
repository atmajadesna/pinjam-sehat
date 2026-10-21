@extends('layout')

@section('judul', 'Pinjaman')

@section('isi')

<div class="kepala-halaman">
    <div>
        <h1>Pinjaman</h1>
        <p>Semua pengajuan dan pinjaman berjalan.</p>
    </div>
    <a class="btn" href="/pinjaman/baru">Pinjaman baru</a>
</div>

<section class="panel">
    <div class="panel__kepala">
        <div style="display: flex; gap: var(--space-2); flex-wrap: wrap;">
            <a class="btn btn--kecil {{ $status ? 'btn--halus' : '' }}" href="/pinjaman">Semua</a>
            <a class="btn btn--kecil {{ $status === 'Aktif' ? '' : 'btn--halus' }}" href="/pinjaman?status=Aktif">Berjalan</a>
            <a class="btn btn--kecil {{ $status === 'Lunas' ? '' : 'btn--halus' }}" href="/pinjaman?status=Lunas">Lunas</a>
        </div>
    </div>
    <div class="panel__badan panel__badan--rapat">
        <div class="tabel-bungkus">
            <table class="data">
                <thead>
                    <tr>
                        <th>Tanggal</th>
                        <th>Anggota</th>
                        <th class="num">Pokok</th>
                        <th class="num">Tenor</th>
                        <th class="num">Angsuran</th>
                        <th class="num">Sisa</th>
                        <th>Status</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($daftar as $p)
                        <tr>
                            <td class="num" style="text-align: left;">{{ $p->tanggal->format('d/m/Y') }}</td>
                            <td>{{ $p->anggota->nama ?? '—' }}</td>
                            <td class="num">Rp{{ number_format($p->pokok, 0, ',', '.') }}</td>
                            <td class="num">{{ $p->tenor }} bln</td>
                            <td class="num">Rp{{ number_format($p->angsuran_bulanan, 0, ',', '.') }}</td>
                            <td class="num">Rp{{ number_format($p->sisa, 0, ',', '.') }}</td>
                            <td>
                                <span class="lencana {{ $p->status === 'Lunas' ? 'lencana--lunas' : 'lencana--aktif' }}">{{ $p->status }}</span>
                            </td>
                            <td><a class="btn btn--halus btn--kecil" href="/pinjaman/{{ $p->id }}">Detail</a></td>
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
