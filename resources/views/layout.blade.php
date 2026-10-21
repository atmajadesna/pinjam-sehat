<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
@php $judul = trim($__env->yieldContent('judul')); @endphp
<title>{{ $judul !== '' ? $judul . ' — ' : '' }}Pinjam Sehat</title>
<meta name="description" content="Pinjam Sehat — sistem informasi pinjaman: simulasi angsuran, data anggota, dan laporan.">
<link rel="icon" href="{{ asset('favicon.svg') }}" type="image/svg+xml">
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@500;600;700;800&family=Inter:wght@400;500;600&family=JetBrains+Mono:wght@500;600&display=swap" rel="stylesheet">
<link rel="stylesheet" href="{{ asset('css/tokens.css') }}">
<link rel="stylesheet" href="{{ asset('css/style.css') }}?v={{ filemtime(public_path('css/style.css')) }}">
</head>
<body>
@include('partials.ikon')
<a class="lewati" href="#isi">Lewati ke isi</a>

<header class="topbar">
    <div class="topbar__dalam">
        <a class="merek" href="{{ session()->has('pengguna') ? '/' : '/simulasi' }}">
            @include('partials.mark')
            <span class="merek__teks">
                <strong>Pinjam Sehat</strong>
            </span>
        </a>

        <nav class="nav" aria-label="Navigasi utama">
            <a href="/" @class(['nav__tautan', 'is-aktif' => request()->is('/')])>Beranda</a>
            <a href="/simulasi" @class(['nav__tautan', 'is-aktif' => request()->is('simulasi')])>Simulasi</a>
            @if (session()->has('pengguna'))
                <a href="/anggota" @class(['nav__tautan', 'is-aktif' => request()->is('anggota*')])>Anggota</a>
                <a href="/pinjaman" @class(['nav__tautan', 'is-aktif' => request()->is('pinjaman*')])>Pinjaman</a>
                @if (session('pengguna.peran') === 'admin')
                    <a href="/laporan" @class(['nav__tautan', 'is-aktif' => request()->is('laporan')])>Laporan</a>
                @endif
            @endif
        </nav>

        <div class="topbar__aksi">
            @if (session()->has('pengguna'))
                <span class="pengguna">
                    {{ session('pengguna.nama') }}
                    <span class="peran peran--{{ session('pengguna.peran') }}">{{ session('pengguna.peran') }}</span>
                </span>
                <form method="POST" action="/logout">
                    @csrf
                    <button class="btn btn--teks btn--mini" type="submit"><svg class="ikon" aria-hidden="true"><use href="#i-keluar"/></svg> Keluar</button>
                </form>
            @else
                <a class="btn btn--kecil" href="/login">Masuk</a>
            @endif
        </div>
    </div>
</header>

<main id="isi" class="wadah">
    @if (session('sukses'))
        <p class="notis notis--sukses">{{ session('sukses') }}</p>
    @endif
    @yield('isi')
</main>

<footer class="kaki">
    <div class="kaki__dalam">
        <p class="kaki__pernyataan">Pinjam sehat: tahu angsurannya sebelum tanda tangan.</p>
        <p class="kaki__kecil">Pinjam Sehat — Sistem Informasi Pinjaman · {{ date('Y') }}</p>
    </div>
</footer>
</body>
</html>
