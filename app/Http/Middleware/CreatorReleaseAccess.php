<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CreatorReleaseAccess
{
    /**
     * Разрешает доступ к управлению релизами
     * только DAO_Root.
     */
    public function handle(
        Request $request,
        Closure $next
    ): Response {

        if (
            !auth()->check() ||
            (int) auth()->id() !==
                (int) config('daodes.root_user_id', 3)
        ) {
            abort(403);
        }

        return $next($request);
    }
}