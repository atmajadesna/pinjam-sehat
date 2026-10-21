@extends('layout')

@section('judul', 'Masuk')

@section('isi')
<div class="masuk">
    <div class="masuk__kartu">
        @include('partials.mark', ['class' => 'mark--besar'])
        <h1>Masuk</h1>
        <p>Gunakan akun petugas untuk mengelola data pinjaman.</p>

        @if ($errors->any())
            <p class="notis notis--gagal">{{ $errors->first() }}</p>
        @endif

        <form method="POST" action="/login">
            @csrf
            <div class="bidang">
                <label for="username">Username</label>
                <input type="text" id="username" name="username" value="{{ old('username') }}" autocomplete="username" required autofocus>
            </div>
            <div class="bidang">
                <label for="password">Password</label>
                <input type="password" id="password" name="password" autocomplete="current-password" required>
            </div>
            <button class="btn" type="submit" style="width: 100%;">Masuk</button>
        </form>

        <p style="margin-top: var(--space-5); font-size: var(--text-xs); color: var(--color-muted);">
            Akun demo — <strong>admin / admin123</strong> atau <strong>petugas / petugas123</strong>
        </p>
    </div>
</div>
@endsection
