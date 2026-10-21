<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        // Aplikasi berjalan di belakang reverse proxy (Cloudflare Tunnel),
        // jadi perlu mempercayai header X-Forwarded-* agar URL HTTPS terbentuk benar.
        $middleware->trustProxies(at: '*');

        $middleware->alias([
            'cek' => \App\Http\Middleware\CekLogin::class,
            'admin' => \App\Http\Middleware\CekAdmin::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();