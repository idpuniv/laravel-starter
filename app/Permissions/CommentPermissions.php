<?php

namespace App\Permissions;

final class CommentPermissions
{
    // Resource permissions
    public const VIEW         = 'comment.view';
    public const LIST         = 'comment.list';
    public const CREATE       = 'comment.create';
    public const UPDATE       = 'comment.update';
    public const DELETE       = 'comment.delete';
    public const RESTORE      = 'comment.restore';
    public const FORCE_DELETE = 'comment.force-delete';
    public const MODERATE     = 'comment.moderate';

    public const GUARD = 'web';

    /**
     * Get human-readable labels for permissions.
     */
    public static function labels(): array
    {
        return [
            self::VIEW         => 'Voir Commentaire',
            self::LIST         => 'Lister les Commentaires',
            self::CREATE       => 'Créer Commentaire',
            self::UPDATE       => 'Modifier Commentaire',
            self::DELETE       => 'Supprimer Commentaire',
            self::RESTORE      => 'Restaurer Commentaire',
            self::FORCE_DELETE => 'Supprimer définitivement Commentaire',
            self::MODERATE     => 'Modérer les Commentaires',
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
            self::MODERATE,
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
