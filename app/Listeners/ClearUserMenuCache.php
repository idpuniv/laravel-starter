<?php

namespace App\Listeners;

use Illuminate\Support\Facades\Cache;
use App\Events\PermissionsUpdated;

class ClearUserMenuCache
{
    public function handle(PermissionsUpdated $event): void
    {
        $userId = $event->user->id;
        Cache::forget("menus.sidebar.{$userId}");
        Cache::forget("menus.navbar.{$userId}");
    }
}