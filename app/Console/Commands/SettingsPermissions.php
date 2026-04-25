<?php

namespace App\Permissions;

final class SettingsPermissions
{
    // Resource permissions
    public const VIEW   = 'settings.view';
    public const LIST   = 'settings.list';
    public const CREATE = 'settings.create';
    public const UPDATE = 'settings.update';
    public const DELETE = 'settings.delete';
    public const GUARD  = 'web';

    /**
     * Get human-readable labels for permissions.
     */
    public static function labels(): array
    {
        return [
            self::VIEW   => 'Voir Settings',
            self::LIST   => 'Lister les Settingss',
            self::CREATE => 'Créer Settings',
            self::UPDATE => 'Modifier Settings',
            self::DELETE => 'Supprimer Settings',
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
