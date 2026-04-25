<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Menus\Menus;

class MenuSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('menus')->truncate();
        foreach (Menus::all() as $type => $menus) {
            $this->insertMenus($menus, $type);
        }

        $this->command->info('Menus seedés avec succès !');
    }

    private function insertMenus(array $menus, string $type, $parentId = null): void
    {
        foreach ($menus as $menu) {

            $id = DB::table('menus')->insertGetId([
                'slug' => $menu['slug'],
                'label' => $menu['label'] ?? null,
                'icon' => $menu['icon'] ?? null,
                'route' => $menu['route'] ?? null,
                'order' => $menu['order'] ?? 0,
                'permission' => $menu['permission'] ?? null,
                'type' => $type,
                'menu_type' => $menu['menu_type'] ?? 'link',
                'parent_id' => $parentId,
                'is_active' => true,
                'is_visible' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            // enfants (sidebar ou navbar → même logique)
            $children = $menu['children'] ?? $menu['items'] ?? [];

            if (!empty($children)) {
                $this->insertMenus($children, $type, $id);
            }
        }
    }
}