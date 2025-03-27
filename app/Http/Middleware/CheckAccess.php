<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CheckAccess
{
    public function handle(Request $request, Closure $next)
    {
        if (auth()->check() && auth()->user()->rang_access >= 3) {
            return $next($request);
        }

        return redirect()->route('home')->with('error', __('message.insufficient_permissions'));
    }
}