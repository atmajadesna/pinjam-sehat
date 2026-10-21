@extends('layout')

@section('judul', 'Simulasi Angsuran')

@section('isi')

<div class="kepala-halaman">
    <div>
        <h1>Simulasi angsuran</h1>
        <p>Masukkan nominal pinjaman, lihat angsuran tiap tenor sebelum memutuskan.</p>
    </div>
</div>

<div class="kalkulator">
    <section class="panel" style="margin-bottom: 0;">
        <div class="panel__kepala"><h2>Nominal</h2></div>
        <div class="panel__badan">
            <form method="GET" action="/simulasi">
                <div class="bidang">
                    <label for="pokok">Jumlah pinjaman</label>
                    <input type="number" id="pokok" name="pokok" value="{{ $pokok }}" min="500000" max="50000000" step="100000">
                    <p class="petunjuk">Minimal Rp500.000 — maksimal Rp50.000.000.</p>
                </div>
                <div class="bidang">
                    <label for="bunga">Bunga per bulan (%)</label>
                    <input type="number" id="bunga" name="bunga" value="{{ $bunga }}" min="0" max="5" step="0.1">
                    <p class="petunjuk">Bunga flat. Default 1,5% per bulan.</p>
                </div>
                <input type="hidden" name="tenor" value="{{ $dipilih }}">
                <button class="btn" type="submit" style="width: 100%;">Hitung</button>
            </form>
        </div>
    </section>

    <div>
        <section class="panel">
            <div class="panel__kepala">
                <h2>Angsuran per tenor</h2>
                <span class="lencana lencana--aktif">Bunga flat {{ rtrim(rtrim(number_format($bunga, 2, ',', '.'), '0'), ',') }}%/bulan</span>
            </div>
            <div class="panel__badan">
                <div class="tenor-grid">
                    @foreach ($hasil as $h)
                        <a class="tenor {{ $h['tenor'] === $dipilih ? 'is-pilih' : '' }}"
                           href="/simulasi?pokok={{ $pokok }}&bunga={{ $bunga }}&tenor={{ $h['tenor'] }}">
                            <span class="tenor__bulan">{{ $h['tenor'] }} bulan</span>
                            <span class="tenor__nilai">Rp{{ number_format($h['per_bulan'], 0, ',', '.') }}</span>
                            <span class="tenor__bulan" style="margin-top: 4px;">per bulan</span>
                            <span class="tenor__tanda">✓ dipilih</span>
                        </a>
                    @endforeach
                </div>
                <p style="font-size: var(--text-xs); color: var(--color-muted);">
                    Angka di atas adalah angsuran pokok + bunga tiap bulan. Total yang dibayar
                    sampai lunas lebih besar dari nominal pinjaman — itu bunganya.
                </p>
            </div>
        </section>

        @if ($rincian)
            <section class="panel">
                <div class="panel__kepala">
                    <h2>Rincian tenor {{ $rincian['tenor'] }} bulan</h2>
                </div>
                <div class="panel__badan">
                    <div class="ringkasan">
                        <div class="ringkasan__kotak">
                            <p class="ringkasan__label">Angsuran / bulan</p>
                            <p class="ringkasan__nilai">Rp{{ number_format($rincian['per_bulan'], 0, ',', '.') }}</p>
                        </div>
                        <div class="ringkasan__kotak">
                            <p class="ringkasan__label">Bunga / bulan</p>
                            <p class="ringkasan__nilai">Rp{{ number_format($rincian['bunga_per_bulan'], 0, ',', '.') }}</p>
                        </div>
                        <div class="ringkasan__kotak">
                            <p class="ringkasan__label">Total bunga</p>
                            <p class="ringkasan__nilai">Rp{{ number_format($rincian['bunga_total'], 0, ',', '.') }}</p>
                        </div>
                        <div class="ringkasan__kotak">
                            <p class="ringkasan__label">Total dibayar</p>
                            <p class="ringkasan__nilai">Rp{{ number_format($rincian['total_bayar'], 0, ',', '.') }}</p>
                        </div>
                    </div>

                    <div class="tabel-bungkus">
                        <table class="data">
                            <thead>
                                <tr>
                                    <th>Angsuran ke</th>
                                    <th class="num">Pokok</th>
                                    <th class="num">Bunga</th>
                                    <th class="num">Jumlah bayar</th>
                                    <th class="num">Sisa pinjaman</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                    $sisaPokok = $rincian['pokok'];
                                    $pokokPerBulan = (int) round($rincian['pokok'] / $rincian['tenor']);
                                @endphp
                                @for ($i = 1; $i <= $rincian['tenor']; $i++)
                                    @php
                                        $pokokBaris = ($i === $rincian['tenor']) ? $sisaPokok : $pokokPerBulan;
                                        $sisaPokok -= $pokokBaris;
                                    @endphp
                                    <tr>
                                        <td class="num">{{ $i }}</td>
                                        <td class="num">Rp{{ number_format($pokokBaris, 0, ',', '.') }}</td>
                                        <td class="num">Rp{{ number_format($rincian['bunga_per_bulan'], 0, ',', '.') }}</td>
                                        <td class="num">Rp{{ number_format($pokokBaris + $rincian['bunga_per_bulan'], 0, ',', '.') }}</td>
                                        <td class="num">Rp{{ number_format($sisaPokok, 0, ',', '.') }}</td>
                                    </tr>
                                @endfor
                            </tbody>
                        </table>
                    </div>
                </div>
            </section>
        @endif
    </div>
</div>

@endsection
