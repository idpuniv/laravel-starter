<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;

class ViewServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {

        View::composer('*', function ($view) {
            $user = auth()->user();

            // Resolve layout and home dynamically based on application context,
            // determined by both the authenticated user's role and the current route.
            if (!$user) {
                $view->with('layout', 'layouts.guest-layout');
                $view->with('home', url('/'));
                return;
            }

            if ($user->is_admin && request()->routeIs('admin.*')) {
                $view->with('layout', 'layouts.admin-layout');
                $view->with('home', route('admin.dashboard'));
                return;
            }

            $view->with('layout', 'layouts.guest-layout');
            $view->with('home', route('dashboard'));
        });
    }
}
