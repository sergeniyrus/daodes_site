<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use App\Http\Middleware\UpdateLastSeenAt;
use App\Http\Middleware\CreatorReleaseAccess;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {

    $middleware->web([
        \App\Http\Middleware\VerifyCsrfToken::class,
        \Illuminate\Http\Middleware\HandleCors::class,
        \App\Http\Middleware\SetLocale::class,
        \App\Http\Middleware\CheckCookieConsent::class,
    ]);

    $middleware->alias([
        'organization.manager' => \App\Http\Middleware\OrganizationManagerMiddleware::class,
        'organization.member' => \App\Http\Middleware\OrganizationMemberMiddleware::class,
        'creator.release' => CreatorReleaseAccess::class,
    ]);
})
    ->withExceptions(function (Exceptions $exceptions) {
        // Настройка обработки исключений
    })->create();