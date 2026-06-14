<?php

namespace App\Menus;

use App\Permissions\CategoryPermissions;
use App\Permissions\PersonPermissions;
use App\Permissions\PostPermissions;
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
                    'route' => 'admin.dashboard',
                ],

                [
                    'slug' => 'statistiques',
                    'label' => 'Statistiques',
                    'icon' => 'fas fa-chart-line',
                    'route' => 'statistics',
                ],

                [
                    'slug' => 'users',
                    'label' => 'Utilisateurs',
                    'icon' => 'fas fa-users',
                    'children' => [
                        [
                            'slug' => 'users.list',
                            'label' => 'Liste des utilisateurs',
                            'route' => 'admin.people.index',
                            'permission' => PersonPermissions::VIEW
                        ],
                        [
                            'slug' => 'admin.users.roles',
                            'label' => 'Rôles & permissions',
                            'route' => 'admin.roles.index',
                        ],
                    ]
                ],

                [
                    'slug' => 'categories',
                    'label' => 'Catégories',
                    'icon' => 'fas fa-chart-line',
                    'route' => 'admin.categories.index',
                    'permission' => CategoryPermissions::LIST
                ],

                [
                    'slug' => 'actualites',
                    'label' => 'Actualités',
                    'icon' => 'fas fa-chart-line',
                    'route' => 'admin.posts.index',
                    'permission' => PostPermissions::LIST
                ],

                [
                    'slug' => 'settings',
                    'label' => 'Paramètres',
                    'icon' => 'fas fa-cog',
                    'children' => [
                        [
                            'slug' => 'settings.general',
                            'label' => 'Généraux',
                            'route' => 'settings.index',
                            'permission' => SystemPermissions::VIEW_SETTINGS,
                        ],
                        [
                            'slug' => 'settings.maintenance',
                            'label' => 'Maintenance',
                            'route' => 'maintenance.index',
                            'permission' => SystemPermissions::VIEW_MAINTENANCE,
                        ],
                        [
                            'slug' => 'settings.cache',
                            'label' => 'Cache',
                            'route' => 'cache.index',
                            'permission' => SystemPermissions::VIEW_CACHE,
                        ],
                    ]
                ],
                [
                    'slug' => 'taches',
                    'label' => 'Tâches',
                    'icon' => 'fas fa-tasks',
                    'route' => 'admin.job-tracking.index',
                    'permission' => SystemPermissions::VIEW_JOBS,
                ],
                [
                    'slug' => 'audit',
                    'label' => 'Audit',
                    'icon' => 'fas fa-search',
                    'route' => 'admin.audit.index',
                    'permission' => SystemPermissions::VIEW_AUDIT,
                ],

                [
                    'slug' => 'account',
                    'label' => 'Mon compte',
                    'icon' => 'fas fa-user-circle',
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
                ],

                [
                    'slug' => 'notifications',
                    'label' => 'Notifications',
                    'menu_type' => 'dropdown',
                    'items' => [
                        [
                            'slug' => 'notification.view_all',
                            'label' => 'Voir toutes',
                            'route' => '',
                        ],
                    ]
                ],

                [
                    'slug' => 'user',
                    'menu_type' => 'dropdown',
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