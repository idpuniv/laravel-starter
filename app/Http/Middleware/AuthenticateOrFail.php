<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Auth\Middleware\Authenticate as BaseAuthenticate;
use Symfony\Component\HttpFoundation\Response;

class AuthenticateOrFail extends BaseAuthenticate
{
    /**
     * Gère la requête entrante.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string  ...$guards
     * @return mixed
     *
     * @throws \Illuminate\Auth\AuthenticationException
     */
    public function handle($request, Closure $next, ...$guards)
    {
        $isMode404 = false;
        if (! empty($guards) && end($guards) === '404') {
            array_pop($guards); // On le retire pour ne pas perturber Laravel
            $isMode404 = true;
        }

        if (empty($guards)) {
            $guards = array_keys(config('auth.guards', []));
        }

        try {
            $this->authenticate($request, $guards);
            
        } catch (AuthenticationException $exception) {
            
            if ($isMode404) {
                abort(Response::HTTP_NOT_FOUND);
            }

            throw $exception;
        }

        return $next($request);
    }
}