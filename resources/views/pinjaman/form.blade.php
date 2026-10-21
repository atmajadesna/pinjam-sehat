@extends('layout')

@section('judul', 'Pinjaman Baru')

@section('isi')

<div class="kepala-halaman">
    <div>
        <h1>Pinjaman baru</h1>
        <p>Jadwal angsuran dibuat otomatis begitu disimpan.</p>
    </div>
</div>

<section class="panel">
    <div class="panel__badan">
        <form method="POST" action="/pinjaman" id="formPinjaman">
            @csrf

            <div class="form-grid">
                <div class="bidang">
                    <label for="anggota_id">Anggota</label>
                    <select id="anggota_id" name="anggota_id" required>
                        <option value="">— pilih anggota —</option>
                        @foreach ($anggota as $a)
                            <option value="{{ $a->id }}" @selected(old('anggota_id') == $a->id)>{{ $a->nama }} — {{ $a->pekerjaan }}</option>
                        @endforeach
                    </select>
                    @error('anggota_id') <p class="galat">{{ $message }}</p> @enderror
                </div>

                <div class="bidang">
                    <label for="tanggal">Tanggal pencairan</label>
                    <input type="date" id="tanggal" name="tanggal" value="{{ old('tanggal', date('Y-m-d')) }}" required>
                    @error('tanggal') <p class="galat">{{ $message }}</p> @enderror
                </div>

                <div class="bidang">
                    <label for="pokok">Jumlah pinjaman</label>
                    <input type="number" id="pokok" name="pokok" value="{{ old('pokok', 5000000) }}" min="500000" max="50000000" step="100000" required>
                    @error('pokok') <p class="galat">{{ $message }}</p> @enderror
                </div>

                <div class="bidang">
                    <label for="tenor">Tenor</label>
                    <select id="tenor" name="tenor" required>
                        @foreach ([3, 6, 12, 18, 24] as $t)
                            <option value="{{ $t }}" @selected(old('tenor', 12) == $t)>{{ $t }} bulan</option>
                        @endforeach
                    </select>
                    @error('tenor') <p class="galat">{{ $message }}</p> @enderror
                </div>

                <div class="bidang">
                    <label for="bunga_persen">Bunga per bulan (%)</label>
                    <input type="number" id="bunga_persen" name="bunga_persen" value="{{ old('bunga_persen', 1.5) }}" min="0" max="5" step="0.1" required>
                    <div class="cip-baris" role="group" aria-label="Pilihan bunga cepat">
                        @foreach ([0.5, 1, 1.5, 2, 3] as $b)
                            <button class="cip" type="button" data-bunga="{{ $b }}">
                                {{ rtrim(rtrim(number_format($b, 1, ',', '.'), '0'), ',') }}%
                                @if ($b <= 1)<span class="cip__catatan">rendah</span>@endif
                            </button>
                        @endforeach
                    </div>
                    @error('bunga_persen') <p class="galat">{{ $message }}</p> @enderror
                </div>

                <div class="bidang">
                    <label for="keperluan">Keperluan</label>
                    <input type="text" id="keperluan" name="keperluan" value="{{ old('keperluan', 'Modal usaha') }}" required>
                    @error('keperluan') <p class="galat">{{ $message }}</p> @enderror
                </div>
            </div>

            <div class="ringkasan" style="margin-top: var(--space-5);">
                <div class="ringkasan__kotak">
                    <p class="ringkasan__label">Angsuran / bulan</p>
                    <p class="ringkasan__nilai" id="pratinjauAngsuran">—</p>
                </div>
                <div class="ringkasan__kotak">
                    <p class="ringkasan__label">Total bunga</p>
                    <p class="ringkasan__nilai" id="pratinjauBunga">—</p>
                </div>
                <div class="ringkasan__kotak">
                    <p class="ringkasan__label">Total dibayar</p>
                    <p class="ringkasan__nilai" id="pratinjauTotal">—</p>
                </div>
            </div>

            <div class="form-aksi">
                <button class="btn" type="submit"><svg class="ikon" aria-hidden="true"><use href="#i-cek"/></svg> Setujui &amp; buat jadwal</button>
                <a class="btn btn--teks" href="/pinjaman">Batal</a>
            </div>
        </form>
    </div>
</section>

<script>
(function () {
    const pokok = document.getElementById('pokok');
    const tenor = document.getElementById('tenor');
    const bunga = document.getElementById('bunga_persen');
    const outA = document.getElementById('pratinjauAngsuran');
    const outB = document.getElementById('pratinjauBunga');
    const outT = document.getElementById('pratinjauTotal');

    const rupiah = (n) => 'Rp' + Math.round(n).toLocaleString('id-ID');

    function hitung() {
        const p = parseFloat(pokok.value) || 0;
        const t = parseInt(tenor.value, 10) || 1;
        const b = parseFloat(bunga.value) || 0;
        const bungaTotal = p * (b / 100) * t;
        const total = p + bungaTotal;
        outA.textContent = rupiah(total / t);
        outB.textContent = rupiah(bungaTotal);
        outT.textContent = rupiah(total);
    }

    document.querySelectorAll('.cip[data-bunga]').forEach((tombol) => {
        tombol.addEventListener('click', () => {
            bunga.value = tombol.dataset.bunga;
            document.querySelectorAll('.cip[data-bunga]').forEach((t) => t.classList.remove('is-aktif'));
            tombol.classList.add('is-aktif');
            hitung();
        });
    });

    [pokok, tenor, bunga].forEach((el) => el.addEventListener('input', hitung));
    hitung();
})();
</script>

@endsection
