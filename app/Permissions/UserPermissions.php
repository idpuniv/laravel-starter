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

    public static function labels(): array
    {
        return [
            self::VIEW   => 'Voir un utilisateur',
            self::LIST   => 'Lister les utilisateurs',
            self::CREATE => 'Créer un utilisateur',
            self::UPDATE => 'Modifier un utilisateur',
            self::DELETE => 'Supprimer un utilisateur',

            self::VIEW_ROLE   => 'Voir un rôle utilisateur',
            self::LIST_ROLE   => 'Lister les rôles utilisateur',
            self::CREATE_ROLE => 'Créer des rôles utilisateur',
            self::UPDATE_ROLE => 'Modifier les rôles utilisateur',
            self::DELETE_ROLE => 'Supprimer les rôles utilisateur',

            self::VIEW_PERMISSION   => 'Voir une permission utilisateur',
            self::LIST_PERMISSION   => 'Lister les permissions utilisateur',
            self::CREATE_PERMISSION => 'Créer des permissions utilisateur',
            self::UPDATE_PERMISSION => 'Modifier les permissions utilisateur',
            self::DELETE_PERMISSION => 'Supprimer les permissions utilisateur',
        ];
    }

    public static function read(): array
    {
        return [
            self::VIEW,
            self::LIST,

            self::VIEW_ROLE,
            self::LIST_ROLE,

            self::VIEW_PERMISSION,
            self::LIST_PERMISSION,
        ];
    }

    public static function write(): array
    {
        return [
            self::CREATE,
            self::UPDATE,
            self::DELETE,

            self::CREATE_ROLE,
            self::UPDATE_ROLE,
            self::DELETE_ROLE,

            self::CREATE_PERMISSION,
            self::UPDATE_PERMISSION,
            self::DELETE_PERMISSION,
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