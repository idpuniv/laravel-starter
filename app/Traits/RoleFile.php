<?php
// app/Traits/RoleFile.php

namespace App\Traits;

trait RoleFile
{
    /**
     * Get all guards
     */
    public static function guards(): array
    {
        return array_keys(self::config());
    }

    /**
     * Get roles for a specific guard
     */
    public static function of(string $guard): array
    {
        $config = self::config();
        
        if (!isset($config[$guard])) {
            return [];
        }
        
        $result = [];
        
        foreach ($config[$guard] as $role => $roleData) {
            $permissions = [];
            
            foreach ($roleData['permissions'] as $permissionClass) {
                if (is_string($permissionClass) && class_exists($permissionClass) && method_exists($permissionClass, 'all')) {
                    $permissions = array_merge($permissions, $permissionClass::all());
                }
            }
            
            $result[$role] = [
                'label' => $roleData['label'] ?? $role,
                'description' => $roleData['description'] ?? null,
                'permissions' => array_unique($permissions),
            ];
        }
        
        return $result;
    }

    /**
     * Get permissions for a specific role
     */
    public static function permissions(string $guard, string $role): array
    {
        return self::of($guard)[$role]['permissions'] ?? [];
    }

    /**
     * Get label for a specific role
     */
    public static function label(string $guard, string $role): string
    {
        return self::of($guard)[$role]['label'] ?? $role;
    }

    /**
     * Get description for a specific role
     */
    public static function description(string $guard, string $role): ?string
    {
        return self::of($guard)[$role]['description'] ?? null;
    }

    /**
     * Check if role exists
     */
    public static function has(string $guard, string $role): bool
    {
        return isset(self::of($guard)[$role]);
    }
    /**
     * Get all role constants
     */
    public static function all(): array
    {
        $reflection = new \ReflectionClass(static::class);
        return array_values($reflection->getConstants());
    }
}