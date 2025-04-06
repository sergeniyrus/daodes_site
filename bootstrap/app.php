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
        $middleware->web([
            \Illuminate\Http\Middleware\HandleCors::class, // Добавлено CORS middleware
            \App\Http\Middleware\SetLocale::class,
            \App\Http\Middleware\CheckCookieConsent::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        // Настройка обработки исключений
    })->create();