<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CekLogin
{
    public function handle(Request $request, Closure $next)
    {
        if (! $request->session()->has('pengguna')) {
            return redirect('/login')->with('pesan', 'Silakan masuk dulu.');
        }

        return $next($request);
    }
}
