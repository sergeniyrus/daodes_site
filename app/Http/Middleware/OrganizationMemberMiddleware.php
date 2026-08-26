<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class OrganizationMemberMiddleware
{
    /**
     * Разрешает доступ только активному участнику организации.
     */
    public function handle(
        Request $request,
        Closure $next
    ): Response {
        $user = $request->user();
        $organization = $request->route('organization');

        abort_unless(
            $user &&
            $organization &&
            $user->isActiveOrganizationMember($organization->id),
            403
        );

        return $next($request);
    }
}