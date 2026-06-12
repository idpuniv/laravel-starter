<?php

namespace App\Permissions;

final class CategoryPermissions
{
    // Resource permissions
    public const VIEW         = 'category.view';
    public const LIST         = 'category.list';
    public const CREATE       = 'category.create';
    public const UPDATE       = 'category.update';
    public const DELETE       = 'category.delete';
    public const RESTORE      = 'category.restore';
    public const FORCE_DELETE = 'category.force-delete';

    public const GUARD = 'web';

    /**
     * Get human-readable labels for permissions.
     */
    public static function labels(): array
    {
        return [
            self::VIEW         => 'Voir Catégorie',
            self::LIST         => 'Lister les Catégories',
            self::CREATE       => 'Créer Catégorie',
            self::UPDATE       => 'Modifier Catégorie',
            self::DELETE       => 'Supprimer Catégorie',
            self::RESTORE      => 'Restaurer Catégorie',
            self::FORCE_DELETE => 'Supprimer définitivement Catégorie',
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
