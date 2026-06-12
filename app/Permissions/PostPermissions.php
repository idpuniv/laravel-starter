<?php

namespace App\Permissions;

final class PostPermissions
{
    // Resource permissions
    public const VIEW         = 'post.view';
    public const LIST         = 'post.list';
    public const CREATE       = 'post.create';
    public const UPDATE       = 'post.update';
    public const DELETE       = 'post.delete';
    public const RESTORE      = 'post.restore';
    public const FORCE_DELETE = 'post.force-delete';

    public const GUARD = 'web';

    /**
     * Get human-readable labels for permissions.
     */
    public static function labels(): array
    {
        return [
            self::VIEW         => 'Voir Article',
            self::LIST         => 'Lister les Articles',
            self::CREATE       => 'Créer Article',
            self::UPDATE       => 'Modifier Article',
            self::DELETE       => 'Supprimer Article',
            self::RESTORE      => 'Restaurer Article',
            self::FORCE_DELETE => 'Supprimer définitivement Article',
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
