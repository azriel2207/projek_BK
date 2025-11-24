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
    ->withMiddleware(function (Middleware $middleware) {
        // Validate CSRF tokens dengan exception yang lebih jelas
        $middleware->validateCsrfTokens(except: [
            // Tambahkan route yang perlu di-exclude jika ada
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();