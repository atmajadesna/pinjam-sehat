@extends('layout')

@section('judul', 'Data Anggota')

@section('isi')

@php $admin = session('pengguna.peran') === 'admin'; @endphp

<div class="kepala-halaman">
    <div>
        <h1>Data anggota</h1>
        <p>{{ $daftar->total() }} anggota terdaftar.</p>
    </div>
    @if ($admin)
    <a class="btn" href="/anggota/tambah"><svg class="ikon" aria-hidden="true"><use href="#i-plus"/></svg> Tambah anggota</a>
    @endif
</div>

<section class="panel">
    <div class="panel__kepala">
        <form method="GET" action="/anggota" style="display: flex; gap: var(--space-2); width: 100%; max-width: 420px;">
            <input type="text" name="cari" value="{{ $cari }}" placeholder="Cari nama, NIK, atau alamat">
            <button class="btn btn--tonal" type="submit"><svg class="ikon" aria-hidden="true"><use href="#i-cari"/></svg> Cari</button>
        </form>
    </div>
    <div class="panel__badan panel__badan--rapat">
        <div class="tabel-bungkus">
            <table class="data">
                <thead>
                    <tr>
                        <th>Nama</th>
                        <th class="tablet-sembunyi">NIK</th>
                        <th>No. HP</th>
                        <th class="hp-sembunyi">Alamat</th>
                        <th class="tablet-sembunyi">Pekerjaan</th>
                        <th class="num tablet-sembunyi">Penghasilan</th>
                        @if ($admin)<th class="kolom-aksi">Aksi</th>@endif
                    </tr>
                </thead>
                <tbody>
                    @forelse ($daftar as $a)
                        <tr>
                            <td>{{ $a->nama }}</td>
                            <td class="num tablet-sembunyi" style="text-align: left;">{{ $a->nik }}</td>
                            <td class="num" style="text-align: left;">{{ $a->no_hp }}</td>
                            <td class="lebar hp-sembunyi">{{ $a->alamat }}</td>
                            <td class="tablet-sembunyi">{{ $a->pekerjaan }}</td>
                            <td class="num tablet-sembunyi">Rp{{ number_format($a->penghasilan, 0, ',', '.') }}</td>
                            @if ($admin)
                            <td class="kolom-aksi">
                                    <div class="aksi-baris">
                                        <a class="btn btn--ikon" href="/anggota/{{ $a->id }}/ubah"
                                           aria-label="Ubah data {{ $a->nama }}" title="Ubah"><svg class="ikon" aria-hidden="true"><use href="#i-ubah"/></svg></a>
                                        <form method="POST" action="/anggota/{{ $a->id }}" onsubmit="return confirm('Hapus anggota ini?')">
                                            @csrf
                                            @method('DELETE')
                                            <button class="btn btn--ikon btn--bahaya" type="submit"
                                                    aria-label="Hapus {{ $a->nama }}" title="Hapus"><svg class="ikon" aria-hidden="true"><use href="#i-hapus"/></svg></button>
                                        </form>
                                    </div>
                                </td>
                            @endif
                        </tr>
                    @empty
                        <tr><td colspan="{{ $admin ? 7 : 6 }}" class="kosong">Belum ada anggota yang cocok.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="paginasi">{{ $daftar->links() }}</div>
    </div>
</section>

@endsection
