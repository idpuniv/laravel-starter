<?php

namespace App\Permissions;

final class UserPermissions
{
    public const GUARD = 'web';

    // User
    public const VIEW   = 'user.view';
    public const LIST   = 'user.list';
    public const CREATE = 'user.create';
    public const UPDATE = 'user.update';
    public const DELETE = 'user.delete';

    // Roles
    public const VIEW_ROLE   = 'user.role.view';
    public const LIST_ROLE   = 'user.role.list';
    public const CREATE_ROLE = 'user.role.create';
    public const UPDATE_ROLE = 'user.role.update';
    public const DELETE_ROLE = 'user.role.delete';

    // Permissions
    public const VIEW_PERMISSION   = 'user.permission.view';
    public const LIST_PERMISSION   = 'user.permission.list';
    public const CREATE_PERMISSION = 'user.permission.create';
    public const UPDATE_PERMISSION = 'user.permission.update';
    public const DELETE_PERMISSION = 'user.permission.delete';

    // Teams (NEW)
    public const VIEW_TEAM   = 'user.team.view';
    public const LIST_TEAM   = 'user.team.list';
    public const CREATE_TEAM = 'user.team.create';
    public const UPDATE_TEAM = 'user.team.update';
    public const DELETE_TEAM = 'user.team.delete';

    // Labels
    public static function labels(): array
    {
        return [
            // User
            self::VIEW   => 'Voir un utilisateur',
            self::LIST   => 'Lister les utilisateurs',
            self::CREATE => 'Créer un utilisateur',
            self::UPDATE => 'Modifier un utilisateur',
            self::DELETE => 'Supprimer un utilisateur',

            // Roles
            self::VIEW_ROLE   => 'Voir un rôle utilisateur',
            self::LIST_ROLE   => 'Lister les rôles utilisateur',
            self::CREATE_ROLE => 'Créer des rôles utilisateur',
            self::UPDATE_ROLE => 'Modifier les rôles utilisateur',
            self::DELETE_ROLE => 'Supprimer les rôles utilisateur',

            // Permissions
            self::VIEW_PERMISSION   => 'Voir une permission utilisateur',
            self::LIST_PERMISSION   => 'Lister les permissions utilisateur',
            self::CREATE_PERMISSION => 'Créer des permissions utilisateur',
            self::UPDATE_PERMISSION => 'Modifier les permissions utilisateur',
            self::DELETE_PERMISSION => 'Supprimer les permissions utilisateur',

            // Teams
            self::VIEW_TEAM   => 'Voir une équipe utilisateur',
            self::LIST_TEAM   => 'Lister les équipes utilisateur',
            self::CREATE_TEAM => 'Créer des équipes utilisateur',
            self::UPDATE_TEAM => 'Modifier les équipes utilisateur',
            self::DELETE_TEAM => 'Supprimer les équipes utilisateur',
        ];
    }

    public static function read(): array
    {
        return [
            // User
            self::VIEW,
            self::LIST,

            // Roles
            self::VIEW_ROLE,
            self::LIST_ROLE,

            // Permissions
            self::VIEW_PERMISSION,
            self::LIST_PERMISSION,

            // Teams
            self::VIEW_TEAM,
            self::LIST_TEAM,
        ];
    }

    public static function write(): array
    {
        return [
            // User
            self::CREATE,
            self::UPDATE,
            self::DELETE,

            // Roles
            self::CREATE_ROLE,
            self::UPDATE_ROLE,
            self::DELETE_ROLE,

            // Permissions
            self::CREATE_PERMISSION,
            self::UPDATE_PERMISSION,
            self::DELETE_PERMISSION,

            // Teams
            self::CREATE_TEAM,
            self::UPDATE_TEAM,
            self::DELETE_TEAM,
        ];
    }

    public static function all(): array
    {
        return array_keys(self::labels());
    }

    public static function guard(): string
    {
        return self::GUARD;
    }
}