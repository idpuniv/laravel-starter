<?php

namespace App\Roles;

use App\Traits\RoleFile;

final class Roles
{
    use RoleFile;
    
    // Role constants
    public const ROOT  = 'root';        // accès total système
    public const ADMIN = 'admin';
    public const USER  = 'user';
    public const VIEWER = 'viewer';
    
    /**
     * Configuration des rôles par guard
     */
    private static function config(): array
    {
        return [
            'web' => [
                self::ADMIN => [
                    'label' => 'Role administrateur',
                    'description' => 'My admin role description',
                    'permissions' => [
                        \App\Permissions\SystemPermissions::class,
                    ],
                ],
                self::USER => [
                    'label' => 'Role utilisateurs sans privilège particulier',
                    'description' => 'Role utilisateur',
                    'permissions' => [
                        
                    ],
                ],
                self::VIEWER => [
                    'label' => 'Lecteur',
                    'description' => 'Role lecture seule',
                    'permissions' => [
                        
                    ],
                ],
                self::ROOT => [
                    'label' => 'Super admin',
                    'description' => 'Role ayant tous les privilèges',
                    'permissions' => [
                        \App\Permissions\SystemPermissions::class,
                        \App\Permissions\RolePermissions::class,
                        \App\Permissions\PermissionPermissions::class,
                        \App\Permissions\UserPermissions::class,
                    ],
                ],
            ],
            // 'admin' => [
            //     self::TEST => [
            //         'label' => 'My test role',
            //         'description' => 'My test role description',
            //         'permissions' => [

            //         ],
            //     ],
            // ],
        ];
    }
}