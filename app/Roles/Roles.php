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
                    'label' => 'Administrateur',
                    'description' => 'Gère les opérations courantes de la plateforme avec des privilèges étendus, sans accès aux paramètres critiques du système.',
                    'permissions' => [
                        \App\Permissions\SystemPermissions::class,
                    ],
                ],
                self::USER => [
                    'label' => 'Utilisateur',
                    'description' => 'Accède aux fonctionnalités de base de l’application selon les droits qui lui sont attribués.',
                    'permissions' => [
                        
                    ],
                ],
                self::VIEWER => [
                    'label' => 'Lecteur',
                    'description' => 'Dispose d’un accès en lecture seule aux données sans possibilité de modification.',
                    'permissions' => [
                        
                    ],
                ],
                self::ROOT => [
                    'label' => 'Super Administrateur',
                    'description' => 'Dispose d’un accès complet à l’ensemble du système, y compris la gestion des rôles, des permissions et des configurations critiques.',
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