<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class Ensure404
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next, string $guard = null)
    {
        if (!auth($guard)->check()) {
            abort(404);
        }

        auth()->shouldUse($guard);

        return $next($request);
    }
}