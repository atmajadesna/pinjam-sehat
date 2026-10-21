@extends('layout')

@section('judul', 'Akses Ditolak')

@section('isi')

<div class="kepala-halaman">
    <div>
        <h1>Nggak punya akses</h1>
        <p>Halaman ini khusus <strong>admin</strong>. Akun petugas nggak bisa buka.</p>
    </div>
</div>

<section class="panel">
    <div class="panel__badan" style="text-align: center; padding: var(--space-7);">
        <p style="font-family: var(--font-mono); font-size: 3rem; font-weight: 600; color: var(--color-accent-soft); line-height: 1;">403</p>
        <p style="margin: var(--space-4) 0 var(--space-5); color: var(--color-muted);">
            Kalau butuh akses ini, minta ke admin.
        </p>
        <a class="btn" href="/">Balik ke beranda</a>
    </div>
</section>

@endsection
