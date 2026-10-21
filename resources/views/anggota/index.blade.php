@extends('layout')

@section('judul', 'Data Anggota')

@section('isi')

<div class="kepala-halaman">
    <div>
        <h1>Data anggota</h1>
        <p>{{ $daftar->total() }} anggota terdaftar.</p>
    </div>
    <a class="btn" href="/anggota/tambah">Tambah anggota</a>
</div>

<section class="panel">
    <div class="panel__kepala">
        <form method="GET" action="/anggota" style="display: flex; gap: var(--space-2); width: 100%; max-width: 420px;">
            <input type="text" name="cari" value="{{ $cari }}" placeholder="Cari nama, NIK, atau alamat">
            <button class="btn btn--halus" type="submit">Cari</button>
        </form>
    </div>
    <div class="panel__badan panel__badan--rapat">
        <div class="tabel-bungkus">
            <table class="data">
                <thead>
                    <tr>
                        <th>Nama</th>
                        <th>NIK</th>
                        <th>No. HP</th>
                        <th>Alamat</th>
                        <th>Pekerjaan</th>
                        <th class="num">Penghasilan</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($daftar as $a)
                        <tr>
                            <td>{{ $a->nama }}</td>
                            <td class="num" style="text-align: left;">{{ $a->nik }}</td>
                            <td class="num" style="text-align: left;">{{ $a->no_hp }}</td>
                            <td class="lebar">{{ $a->alamat }}</td>
                            <td>{{ $a->pekerjaan }}</td>
                            <td class="num">Rp{{ number_format($a->penghasilan, 0, ',', '.') }}</td>
                            <td>
                                <div style="display: flex; gap: var(--space-1);">
                                    <a class="btn btn--halus btn--kecil" href="/anggota/{{ $a->id }}/ubah">Ubah</a>
                                    <form method="POST" action="/anggota/{{ $a->id }}" onsubmit="return confirm('Hapus anggota ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button class="btn btn--bahaya btn--kecil" type="submit">Hapus</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="7" class="kosong">Belum ada anggota yang cocok.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="paginasi">{{ $daftar->links() }}</div>
    </div>
</section>

@endsection
