<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use App\Roles\Roles;

class RolePermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Clear existing data
        DB::table('role_has_permissions')->delete();
        DB::table('permissions')->delete();
        DB::table('modules')->delete();
        DB::table('roles')->delete();

        // Load permissions registry from App\Permissions
        $modules = require app_path('Permissions/PermissionsRegistry.php');
        
        $permissionsData = [];
        
        foreach ($modules as $moduleName => $moduleConfig) {
            $moduleSlug = Str::slug($moduleName);
            $moduleDescription = $moduleConfig['description'] ?? null;
            
            // Create module
            $moduleId = DB::table('modules')->insertGetId([
                'name' => $moduleName,
                'slug' => $moduleSlug,
                'description' => $moduleDescription,
                'icon' => null,
                'order' => 0,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            // Process each permission class in the module
            foreach ($moduleConfig['permissions'] as $permissionClass) {
                if (!class_exists($permissionClass)) {
                    $this->command->warn("Permission class {$permissionClass} not found");
                    continue;
                }

                if (!method_exists($permissionClass, 'all') || !method_exists($permissionClass, 'labels')) {
                    $this->command->warn("Class {$permissionClass} missing required methods (all, labels)");
                    continue;
                }

                $classGuard = method_exists($permissionClass, 'guard') ? $permissionClass::guard() : 'web';

                // Store permission data
                foreach ($permissionClass::all() as $permissionName) {
                    $permissionsData[$permissionName] = [
                        'label' => $permissionClass::labels()[$permissionName] ?? $permissionName,
                        'module_id' => $moduleId,
                        'module_slug' => $moduleSlug,
                        'guard' => $classGuard,
                    ];
                }

                $this->command->line("  - Processed: " . class_basename($permissionClass));
            }

            $this->command->line("Created module: {$moduleName}");
        }

        // Create permissions in database
        foreach ($permissionsData as $permissionName => $data) {
            DB::table('permissions')->updateOrInsert(
                [
                    'name' => $permissionName,
                    'guard_name' => $data['guard'],
                ],
                [
                    'label' => $data['label'],
                    'module_id' => $data['module_id'],
                    'module_slug' => $data['module_slug'],
                ]
            );
        }

        $this->command->info('Permissions created: ' . count($permissionsData));

        // Create roles and assign permissions
        foreach (Roles::guards() as $guard) {
            foreach (Roles::of($guard) as $roleSlug => $roleData) {
                // Create the role
                $roleId = DB::table('roles')->insertGetId([
                    'name' => $roleSlug,
                    'label' => $roleData['label'],
                    'guard_name' => $guard,
                ]);

                $permissionIds = [];

                // Get permissions for this role
                foreach ($roleData['permissions'] as $permissionName) {
                    $permission = DB::table('permissions')
                        ->where('name', $permissionName)
                        ->where('guard_name', $guard)
                        ->first();

                    if ($permission) {
                        $permissionIds[] = $permission->id;
                    } else {
                        $this->command->warn("Permission '{$permissionName}' not found for guard '{$guard}'");
                    }
                }

                // Associate permissions with role
                foreach ($permissionIds as $permId) {
                    DB::table('role_has_permissions')->insert([
                        'role_id' => $roleId,
                        'permission_id' => $permId,
                    ]);
                }

                $this->command->line("Created role: {$roleData['label']} with " . count($permissionIds) . " permissions");
            }
        }

        $this->command->info('Roles and permissions created successfully!');
    }
}