@extends('layout')

@section('judul', $anggota->exists ? 'Ubah Anggota' : 'Tambah Anggota')

@section('isi')

<div class="kepala-halaman">
    <div>
        <h1>{{ $anggota->exists ? 'Ubah anggota' : 'Tambah anggota' }}</h1>
        <p>Data ini dipakai untuk menilai kelayakan pinjaman.</p>
    </div>
</div>

<section class="panel">
    <div class="panel__badan">
        <form method="POST" action="{{ $anggota->exists ? '/anggota/' . $anggota->id : '/anggota' }}">
            @csrf
            @if ($anggota->exists)
                @method('PUT')
            @endif

            <div class="form-grid">
                <div class="bidang">
                    <label for="nama">Nama lengkap</label>
                    <input type="text" id="nama" name="nama" value="{{ old('nama', $anggota->nama) }}" required>
                    @error('nama') <p class="galat">{{ $message }}</p> @enderror
                </div>
                <div class="bidang">
                    <label for="nik">NIK</label>
                    <input type="text" id="nik" name="nik" value="{{ old('nik', $anggota->nik) }}" maxlength="16" required>
                    @error('nik') <p class="galat">{{ $message }}</p> @enderror
                </div>
                <div class="bidang">
                    <label for="no_hp">No. HP</label>
                    <input type="text" id="no_hp" name="no_hp" value="{{ old('no_hp', $anggota->no_hp) }}" required>
                    @error('no_hp') <p class="galat">{{ $message }}</p> @enderror
                </div>
                <div class="bidang">
                    <label for="pekerjaan">Pekerjaan</label>
                    <input type="text" id="pekerjaan" name="pekerjaan" value="{{ old('pekerjaan', $anggota->pekerjaan) }}" required>
                    @error('pekerjaan') <p class="galat">{{ $message }}</p> @enderror
                </div>
                <div class="bidang">
                    <label for="alamat">Alamat</label>
                    <input type="text" id="alamat" name="alamat" value="{{ old('alamat', $anggota->alamat) }}" required>
                    @error('alamat') <p class="galat">{{ $message }}</p> @enderror
                </div>
                <div class="bidang">
                    <label for="penghasilan">Penghasilan per bulan</label>
                    <input type="number" id="penghasilan" name="penghasilan" value="{{ old('penghasilan', $anggota->penghasilan) }}" min="0" step="100000" required>
                    @error('penghasilan') <p class="galat">{{ $message }}</p> @enderror
                </div>
            </div>

            <div class="form-aksi">
                <button class="btn" type="submit"><svg class="ikon" aria-hidden="true"><use href="#i-cek"/></svg> Simpan</button>
                <a class="btn btn--teks" href="/anggota">Batal</a>
            </div>
        </form>
    </div>
</section>

@endsection
