<?php

namespace App\Permissions;

final class PersonPermissions
{
    // Resource permissions
    public const VIEW   = 'person.view';
    public const LIST   = 'person.list';
    public const CREATE = 'person.create';
    public const UPDATE = 'person.update';
    public const DELETE = 'person.delete';
    public const GUARD  = 'web';

    /**
     * Get human-readable labels for permissions.
     */
    public static function labels(): array
    {
        return [
            self::VIEW   => 'Voir Person',
            self::LIST   => 'Lister les Persons',
            self::CREATE => 'Créer Person',
            self::UPDATE => 'Modifier Person',
            self::DELETE => 'Supprimer Person',
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
