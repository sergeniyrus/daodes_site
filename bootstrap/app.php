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
        // Добавьте SuspiciousActivityMiddleware и IpFilterMiddleware перед SetLocale
        $middleware->web([
           // \App\Http\Middleware\SuspiciousActivityMiddleware::class,
           // \App\Http\Middleware\IpFilterMiddleware::class,
            \App\Http\Middleware\SetLocale::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        // Настройка обработки исключений
    })->create();