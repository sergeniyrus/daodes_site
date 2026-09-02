<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ReleaseAdminMiddleware
{
    /**
     * Пользователь DAO_Root.
     */
    private const ROOT_USER_ID = 3;


    /**
     * Проверка доступа к Release Admin Panel.
     */
    public function handle(
        Request $request,
        Closure $next
    ): Response {

        /*
         * ============================================================
         * НЕ АВТОРИЗОВАН
         * ============================================================
         */

        if (!$request->user()) {

            abort(
                403,
                'Требуется авторизация.'
            );

        }


        /*
         * ============================================================
         * ТОЛЬКО DAO_ROOT ID = 3
         * ============================================================
         */

        if (
            (int) $request->user()->id
            !== self::ROOT_USER_ID
        ) {

            abort(
                403,
                'Доступ разрешён только DAO_Root.'
            );

        }


        return $next($request);
    }
}