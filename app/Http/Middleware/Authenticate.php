<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Auth\Middleware\Authenticate as BaseAuthenticate;
use Symfony\Component\HttpFoundation\Response;

class Authenticate extends BaseAuthenticate
{
    public function handle($request, Closure $next, ...$guards)
    {
        if (empty($guards)) {
            $guards = array_keys(config('auth.guards', []));
        }

        try {
            $this->authenticate($request, $guards);
        } catch (AuthenticationException) {
            abort(Response::HTTP_NOT_FOUND);    
        }

        return $next($request);
    }
}