<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AdminMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        // Проверяем, авторизован ли пользователь и имеет ли уровень доступа 3
        if (!auth()->check() || auth()->user()->access_level != 3) {
            abort(403, 'Доступ запрещен — только для администраторов.');
        }

        return $next($request);
    }
}
