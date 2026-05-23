<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ShareContext
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    // app/Http/Middleware/ShareContext.php
    public function handle($request, $next)
    {
        if (auth('admin')->check()) {
            view()->share('layout', 'layouts.admin-layout');
            view()->share('home', route('admin.dashboard'));
        } elseif (auth()->check()) {
            view()->share('layout', 'layouts.app-layout');
            view()->share('home', route('dashboard'));
        } else {
            view()->share('layout', 'layouts.guest-layout');
            view()->share('home', url('/'));
        }

        return $next($request);
    }
}
