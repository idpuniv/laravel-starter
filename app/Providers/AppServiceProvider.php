<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\View;
use App\Contracts\Repositories\PermissionRepositoryInterface;
use App\Repositories\SpatiePermissionRepository;
use Illuminate\Support\Facades\Route;
use App\Roles\Roles;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Gate::before(function ($user, $ability) {
            return $user->hasRole(Roles::ROOT) ? true : null;
        });

        View::composer('*', function ($view) {

            $user = auth()->user();
            $isAdminRoute = request()->is('admin') || request()->is('admin/*');

            if ($user && $user->hasRole('admin') && $isAdminRoute) {

                $view->with('layout', 'layouts.admin-layout');
                $view->with('home', route('admin.dashboard'));
            } elseif ($user) {

                $view->with('layout', 'layouts.app-layout');
                $view->with('home', route('dashboard'));
            } else {

                $view->with('layout', 'layouts.guest-layout');
                $view->with('home', url('/'));
            }
        });
        $this->app->bind(
            PermissionRepositoryInterface::class,
            SpatiePermissionRepository::class
        );
    }
}
