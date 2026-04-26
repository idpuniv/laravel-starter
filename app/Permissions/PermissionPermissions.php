<?php

namespace App\Permissions;

final class PermissionPermissions
{
    // Resource permissions
    public const VIEW   = 'permission.view';
    public const LIST   = 'permission.list';
    public const CREATE = 'permission.create';
    public const UPDATE = 'permission.update';
    public const DELETE = 'permission.delete';
    public const GUARD  = 'web';

    /**
     * Get human-readable labels for permissions.
     */
    public static function labels(): array
    {
        return [
            self::VIEW   => 'Voir Permission',
            self::LIST   => 'Lister les Permissions',
            self::CREATE => 'Créer Permission',
            self::UPDATE => 'Modifier Permission',
            self::DELETE => 'Supprimer Permission',
        ];
    }

    /**
     * Get read permissions (view, list).
     */
    public static function read(): array
    {
        return [self::VIEW, self::LIST];
    }

    /**
     * Get write permissions (create, update, delete).
     */
    public static function write(): array
    {
        return [self::CREATE, self::UPDATE, self::DELETE];
    }

    /**
     * Get the guard name.
     */
    public static function guard(): string
    {
        return self::GUARD;
    }

    /**
     * Get all permission names.
     */
    public static function all(): array
    {
        return array_keys(self::labels());
    }
}
