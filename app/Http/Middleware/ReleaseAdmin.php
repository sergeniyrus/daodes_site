<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ReleaseAdmin
{
    public function handle(
        Request $request,
        Closure $next
    ): Response {

        if (!auth()->check()) {
            abort(401);
        }

        if ((int) auth()->id() !== 3) {
            abort(
                403,
                'Доступ к Release Admin Panel ограничен.'
            );
        }

        return $next($request);
    }
}