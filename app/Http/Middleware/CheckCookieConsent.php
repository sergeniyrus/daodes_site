<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckCookieConsent
{
    public function handle(Request $request, Closure $next)
{
    if ($request->hasCookie('cookie_consent') || 
        $request->is('cookie-policy*') ||
        $request->expectsJson()) {
        return $next($request);
    }

    // Проверяем localStorage через JavaScript
    view()->share('checkLocalStorage', true);
    
    return $next($request);
}
}