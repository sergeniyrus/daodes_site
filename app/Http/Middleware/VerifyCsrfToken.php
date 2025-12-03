<?php

namespace App\Http\Middleware;

use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken as Middleware;

class VerifyCsrfToken extends Middleware
{
    /**
     * Маршруты, исключенные из проверки CSRF.
     */
    protected $except = [
        // 'user/offline',
        // 'user/online',
        // 'debug-offline',
    ];
}
