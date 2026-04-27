<?php

return [

    'Paramètres' => [
        'description' => 'Notification, Sécurité, etc',
        'permissions' => [
            App\Permissions\SystemPermissions::class,
        ],
    ],

    'Roles et Permissions' => [
        'description' => 'Gestion des Roles et Permissions',
        'permissions' => [
            App\Permissions\RolePermissions::class,
            App\Permissions\PermissionPermissions::class,
        ],
        'icon' => 'heroicon-o-folder',
        'order' => 0,
    ],

    'Utilisateurs' => [
        'description' => 'Gestion des Utilisateurs',
        'permissions' => [
            App\Permissions\UserPermissions::class,
        ],
        'icon' => 'heroicon-o-folder',
        'order' => 0,
    ],
];
