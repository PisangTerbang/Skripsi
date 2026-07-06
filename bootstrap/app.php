<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__ . '/../routes/web.php',
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {

        $middleware->alias([
            'role' => \App\Http\Middleware\RoleMiddleware::class,
        ]);

        // Percaya reverse proxy (ngrok, dll.) agar Laravel membaca X-Forwarded-Proto
        // dan mendeteksi HTTPS dengan benar — mencegah URL http:// (mixed content)
        // saat diakses lewat tunnel HTTPS.
        $middleware->trustProxies(at: '*');

    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
