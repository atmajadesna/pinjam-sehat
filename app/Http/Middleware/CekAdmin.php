<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Membatasi halaman tertentu hanya untuk peran admin.
 * Petugas tetap bisa bekerja (anggota, pinjaman, angsuran),
 * tapi tidak boleh mengubah data induk atau melihat laporan.
 */
class CekAdmin
{
    public function handle(Request $request, Closure $next): Response
    {
        if (session('pengguna.peran') !== 'admin') {
            abort(403, 'Halaman ini khusus admin.');
        }

        return $next($request);
    }
}
