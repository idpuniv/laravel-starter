<?php

namespace App\Permissions;

final class RolePermissions
{
    // Resource permissions
    public const VIEW   = 'role.view';
    public const LIST   = 'role.list';
    public const CREATE = 'role.create';
    public const UPDATE = 'role.update';
    public const DELETE = 'role.delete';
    public const GUARD  = 'web';

    /**
     * Get human-readable labels for permissions.
     */
    public static function labels(): array
    {
        return [
            self::VIEW   => 'Voir Role',
            self::LIST   => 'Lister les Roles',
            self::CREATE => 'Créer Role',
            self::UPDATE => 'Modifier Role',
            self::DELETE => 'Supprimer Role',
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
