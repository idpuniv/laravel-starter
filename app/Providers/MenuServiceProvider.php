<?php

namespace App\Providers;

use Illuminate\Support\Facades\View;
use App\Facades\Menu;
use App\Services\MenuService;

use Illuminate\Support\ServiceProvider;

class MenuServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->singleton('menu.service', function () {
            return new MenuService();
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        View::composer(['layouts.admin-layout'], function ($view) {
            $view->with([
                'sidebarMenus' => Menu::get('sidebar'),
            ]);
        });
    }
}
