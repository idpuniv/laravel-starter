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
    // Roles in groupe context
    public const OWNER = 'owner';
    public const MANAGER = 'manager';
    public const MEMBER = 'member';
    
    /**
     * Configuration des rôles par guard
     */
    private static function config(): array
    {
        return [
            'web' => [
                self::ROOT => [
                    'team' => 'root',
                    'label' => 'Super Administrateur',
                    'description' => 'Dispose d’un accès complet à l’ensemble du système, y compris la gestion des rôles, des permissions et des configurations critiques.',
                    'permissions' => [
                        
                    ],
                ],
                 self::OWNER => [
                    'team' => 'admin',
                    'label' => 'Propriétaire de groupe',
                    'description' => 'Possède tous les droits sur les ressources du groupe, y compris la gestion des membres et des permissions.',
                    'permissions' => [
                        
                    ],
                ],
                self::MANAGER => [
                    'team' => 'admin',
                    'label' => 'Gestionnaire de groupe',
                    'description' => 'Peut gérer les ressources du groupe, y compris la gestion des membres et des permissions.',
                    'permissions' => [
                        \App\Permissions\SystemPermissions::class,
                        \App\Permissions\RolePermissions::class,
                        \App\Permissions\PermissionPermissions::class,
                        \App\Permissions\UserPermissions::class,
                        // \App\Permissions\PersonPermissions::class,
                        \App\Permissions\TeamPermissions::class,
                        
                    ],
                ],
                self::MEMBER => [
                    'team' => 'default',
                    'label' => 'Membre de groupe',
                    'description' => 'Peut accéder aux ressources du groupe selon les droits qui lui sont attribués.',
                    'permissions' => [
                        
                    ],
                ],
                self::ADMIN => [
                    'team' => 'admin',
                    'label' => 'Administrateur',
                    'description' => 'A accès a la plateforme avec les droits qui lui sont attribués, mais ne peut pas gérer les rôles et permissions.',
                    'permissions' => [
                       
                    ],
                ],
                self::USER => [
                    'label' => 'Utilisateur',
                    'description' => 'Accède aux fonctionnalités de base de l’application selon les droits qui lui sont attribués.',
                    'permissions' => [
                        
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