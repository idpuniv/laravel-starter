<?php

namespace App\Permissions;

final class TeamPermissions
{
    // Resource permissions
    public const VIEW   = 'team.view';
    public const LIST   = 'team.list';
    public const CREATE = 'team.create';
    public const UPDATE = 'team.update';
    public const DELETE = 'team.delete';
    public const GUARD  = 'web';

    /**
     * Get human-readable labels for permissions.
     */
    public static function labels(): array
    {
        return [
            self::VIEW   => 'Voir Team',
            self::LIST   => 'Lister les Teams',
            self::CREATE => 'Créer Team',
            self::UPDATE => 'Modifier Team',
            self::DELETE => 'Supprimer Team',
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
