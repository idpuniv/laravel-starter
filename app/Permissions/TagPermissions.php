<?php

namespace App\Permissions;

final class TagPermissions
{
    // Resource permissions
    public const VIEW         = 'tag.view';
    public const LIST         = 'tag.list';
    public const CREATE       = 'tag.create';
    public const UPDATE       = 'tag.update';
    public const DELETE       = 'tag.delete';
    public const RESTORE      = 'tag.restore';
    public const FORCE_DELETE = 'tag.force-delete';

    public const GUARD = 'web';

    /**
     * Get human-readable labels for permissions.
     */
    public static function labels(): array
    {
        return [
            self::VIEW         => 'Voir Étiquette',
            self::LIST         => 'Lister les Étiquettes',
            self::CREATE       => 'Créer Étiquette',
            self::UPDATE       => 'Modifier Étiquette',
            self::DELETE       => 'Supprimer Étiquette',
            self::RESTORE      => 'Restaurer Étiquette',
            self::FORCE_DELETE => 'Supprimer définitivement Étiquette',
        ];
    }

    /**
     * Get read permissions (view, list).
     */
    public static function read(): array
    {
        return [
            self::VIEW,
            self::LIST,
        ];
    }

    /**
     * Get write permissions.
     */
    public static function write(): array
    {
        return [
            self::CREATE,
            self::UPDATE,
            self::DELETE,
            self::RESTORE,
            self::FORCE_DELETE,
        ];
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
