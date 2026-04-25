<?php

namespace App\Services;

use App\Models\Menu;
use App\Menus\Menus;
use Illuminate\Support\Facades\Cache;

class MenuService
{
    public function get(string $type): array
    {
        $user = auth()->user();

        return Cache::remember("menus.$type." . ($user?->id ?? 0), 3600, function () use ($type, $user) {
            $menus = $this->load($type);
            return $this->filter($menus, $user);
        });
    }

    private function load(string $type): array
    {
        $db = Menu::where('type', $type)
            ->whereNull('parent_id')
            ->where('is_visible', true)
            ->where('is_active', true)
            ->orderBy('order')
            ->with('children')
            ->get();

        if ($db->isNotEmpty()) {
            return $this->fromDb($db);
        }

        return Menus::all()[$type] ?? [];
    }

    private function fromDb($menus): array
    {
        return $menus->map(function ($menu) {

            return [
                'slug' => $menu->slug,
                'label' => $menu->label,
                'icon' => $menu->icon,
                'route' => $menu->route,
                'menu_type' => $menu->menu_type,

                'permission' => $menu->permission,
                'order' => $menu->order,

                'children' => $menu->children->map(function ($child) use ($menu) {

                    return [
                        'slug' => $child->slug,
                        'label' => $child->label,
                        'icon' => $child->icon,
                        'route' => $child->route,
                        'menu_type' => $child->menu_type,

                        'permission' => $child->permission ?? $menu->permission,

                        'order' => $child->order,
                    ];
                })->toArray(),
            ];
        })->toArray();
    }

    private function filter(array $menus, $user): array
    {
        return collect($menus)
            ->filter(function ($menu) use ($user) {

                if (!empty($menu['permission']) && !$user?->can($menu['permission'])) {
                    return false;
                }

                return true;
            })
            ->map(function ($menu) use ($user) {

                if (!empty($menu['children'])) {
                    $menu['children'] = $this->filter($menu['children'], $user);
                }

                return $menu;
            })
            ->values()
            ->toArray();
    }
}
