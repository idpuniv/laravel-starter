<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class SetSessionLifetime
{
    public function handle(Request $request, Closure $next)
    {
        $response = $next($request);
        
        if (auth()->check()) {
            $user = auth()->user();
            
            if ($user->is_admin) {
                config(['session.lifetime' => config('session.lifetimes.admin', 60)]);
            } else {
                config(['session.lifetime' => config('session.lifetimes.user', 480)]);
            }
        }
        
        return $response;
    }
}