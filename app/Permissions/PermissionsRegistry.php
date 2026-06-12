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
            App\Permissions\PersonPermissions::class,
        ],
        'icon' => 'heroicon-o-folder',
        'order' => 0,
    ],

    'Blog' => [
        'description' => 'Gestion des Articles, Catégories, Étiquettes et Commentaires',
        'permissions' => [
            App\Permissions\PostPermissions::class,
            App\Permissions\CategoryPermissions::class,
            App\Permissions\TagPermissions::class,
            App\Permissions\CommentPermissions::class,
        ],
        'icon' => 'heroicon-o-folder',
        'order' => 0,
    ],
];
