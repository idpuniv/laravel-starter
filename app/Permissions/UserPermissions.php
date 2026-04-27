<?php

namespace App\Permissions;

final class UserPermissions
{
    // Resource permissions
    public const VIEW   = 'user.view';
    public const LIST   = 'user.list';
    public const CREATE = 'user.create';
    public const UPDATE = 'user.update';
    public const DELETE = 'user.delete';
    public const GUARD  = 'web';

    /**
     * Get human-readable labels for permissions.
     */
    public static function labels(): array
    {
        return [
            self::VIEW   => 'Voir User',
            self::LIST   => 'Lister les Users',
            self::CREATE => 'Créer User',
            self::UPDATE => 'Modifier User',
            self::DELETE => 'Supprimer User',
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
