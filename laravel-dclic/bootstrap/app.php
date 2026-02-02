<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->alias([
            'role' => \App\Http\Middleware\CheckRole::class,
            'verify.code' => \App\Http\Middleware\CheckVerificationCode::class,
        ]);
        
        // Appliquer le middleware de vérification du code à toutes les routes authentifiées
        $middleware->web(\App\Http\Middleware\CheckVerificationCode::class);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
