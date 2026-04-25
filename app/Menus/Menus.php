<?php

namespace App\Menus;

use App\Permissions\SystemPermissions;

final class Menus
{
    public static function all(): array
    {
        return [

            'sidebar' => [

                [
                    'slug' => 'dashboard',
                    'label' => 'Tableau de bord',
                    'icon' => 'fas fa-home',
                    'route' => 'dashboard',
                    'order' => 1,
                ],

                [
                    'slug' => 'statistiques',
                    'label' => 'Statistiques',
                    'icon' => 'fas fa-chart-line',
                    'route' => 'statistics',
                    'order' => 2,
                ],

                [
                    'slug' => 'users',
                    'label' => 'Utilisateurs',
                    'icon' => 'fas fa-users',
                    'order' => 3,
                    'children' => [
                        [
                            'slug' => 'users.list',
                            'label' => 'Liste des utilisateurs',
                            'route' => 'users.index',
                        ],
                        [
                            'slug' => 'users.roles',
                            'label' => 'Rôles & permissions',
                            'route' => 'roles.index',
                        ],
                        [
                            'slug' => 'users.groups',
                            'label' => 'Groupes',
                            'route' => 'groups.index',
                        ],
                    ]
                ],

                [
                    'slug' => 'security',
                    'label' => 'Sécurité',
                    'icon' => 'fas fa-shield-alt',
                    'order' => 4,
                    'permission' => SystemPermissions::VIEW_SECURITY,
                    'children' => [
                        [
                            'slug' => 'security.settings',
                            'label' => 'Paramètres sécurité',
                            'route' => 'security.settings',
                            'permission' => SystemPermissions::VIEW_SECURITY,
                        ],
                        [
                            'slug' => 'security.logs',
                            'label' => 'Logs & audit',
                            'route' => 'security.logs',
                            'permission' => SystemPermissions::VIEW_LOGS,
                        ],
                    ]
                ],

                [
                    'slug' => 'settings',
                    'label' => 'Paramètres',
                    'icon' => 'fas fa-cog',
                    'order' => 5,
                    'permission' => SystemPermissions::VIEW_SETTINGS,
                    'children' => [
                        [
                            'slug' => 'settings.general',
                            'label' => 'Généraux',
                            'route' => 'settings.general',
                            'permission' => SystemPermissions::VIEW_SETTINGS,
                        ],
                        [
                            'slug' => 'settings.maintenance',
                            'label' => 'Maintenance',
                            'route' => 'settings.maintenance',
                            'permission' => SystemPermissions::VIEW_MAINTENANCE,
                        ],
                        [
                            'slug' => 'settings.cache',
                            'label' => 'Cache',
                            'route' => 'settings.cache',
                            'permission' => SystemPermissions::VIEW_CACHE,
                        ],
                    ]
                ],

                [
                    'slug' => 'account',
                    'label' => 'Mon compte',
                    'icon' => 'fas fa-user-circle',
                    'order' => 6,
                    'children' => [
                        [
                            'slug' => 'account.profile',
                            'label' => 'Profil',
                            'route' => 'profile.edit',
                        ],
                        [
                            'slug' => 'account.password',
                            'label' => 'Mot de passe',
                            'route' => 'password.edit',
                        ],
                    ]
                ],
            ],

            'navbar' => [

                [
                    'slug' => 'search',
                    'label' => 'Rechercher',
                    'menu_type' => 'search',
                    'order' => 1,
                ],

                [
                    'slug' => 'notifications',
                    'label' => 'Notifications',
                    'menu_type' => 'dropdown',
                    'order' => 2,
                    'items' => [
                        [
                            'slug' => 'notification.view_all',
                            'label' => 'Voir toutes',
                            'route' => 'notifications.index',
                        ],
                    ]
                ],

                [
                    'slug' => 'user',
                    'menu_type' => 'dropdown',
                    'order' => 3,
                    'items' => [
                        [
                            'slug' => 'profile',
                            'label' => 'Mon profil',
                            'route' => 'profile.edit',
                        ],
                        [
                            'slug' => 'logout',
                            'label' => 'Déconnexion',
                            'menu_type' => 'logout',
                            'route' => 'logout',
                        ],
                    ]
                ],
            ],
        ];
    }
}