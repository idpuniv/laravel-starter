<?php

namespace App\Enums;

final class Status
{
    /*
    |--------------------------------------------------------------------------
    | STATUTS MÉTIER
    |--------------------------------------------------------------------------
    */
    const DRAFT      = 'draft';
    const PENDING    = 'pending';
    const ACTIVE     = 'active';
    const INACTIVE   = 'inactive';
    const CONFIRMED  = 'confirmed';
    const COMPLETED  = 'completed';
    const CANCELLED  = 'cancelled';
    const FAILED     = 'failed';
    const ERROR      = 'error';
    const SUCCESS    = 'success';
    const PAID       = 'paid';
    const UNPAID     = 'unpaid';
    const PARTIAL    = 'partial';
    const REFUNDED   = 'refunded';
    const ARCHIVED   = 'archived';

    /*
    |--------------------------------------------------------------------------
    | STATUTS ACTIONS CRUD (AJOUTÉS POUR TON CONTROLLER)
    |--------------------------------------------------------------------------
    */
    const CREATED    = 'created';
    const UPDATED    = 'updated';
    const DELETED    = 'deleted';

    /*
    |--------------------------------------------------------------------------
    | LABELS
    |--------------------------------------------------------------------------
    */
    public static function label(string $status): string
    {
        return match ($status) {
            self::DRAFT => 'Brouillon',
            self::PENDING => 'En attente',
            self::ACTIVE => 'Actif',
            self::INACTIVE => 'Inactif',
            self::CONFIRMED => 'Confirmé',
            self::COMPLETED => 'Terminé',
            self::CANCELLED => 'Annulé',
            self::FAILED => 'Échec',
            self::ERROR => 'Erreur',
            self::SUCCESS => 'Succès',
            self::PAID => 'Payé',
            self::UNPAID => 'Non payé',
            self::PARTIAL => 'Partiel',
            self::REFUNDED => 'Remboursé',
            self::ARCHIVED => 'Archivé',

            self::CREATED => 'Créé',
            self::UPDATED => 'Mis à jour',
            self::DELETED => 'Supprimé',

            default => $status,
        };
    }

    /*
    |--------------------------------------------------------------------------
    | MESSAGES GÉNÉRIQUES
    |--------------------------------------------------------------------------
    */
    public static function message(string $status, ?string $entity = null): string
    {
        return match ($status) {

            self::SUCCESS => "Opération réussie",

            self::ERROR => "Une erreur système est survenue",

            self::FAILED => "Échec de l'opération",

            self::COMPLETED => "Opération terminée",

            self::CANCELLED => "Opération annulée",

            self::CREATED => $entity
                ? "{$entity} créé avec succès"
                : "Création réussie",

            self::UPDATED => $entity
                ? "{$entity} mis à jour avec succès"
                : "Mise à jour réussie",

            self::DELETED => $entity
                ? "{$entity} supprimé avec succès"
                : "Suppression réussie",

            default => "Opération effectuée",
        };
    }

    /*
    |--------------------------------------------------------------------------
    | BADGES
    |--------------------------------------------------------------------------
    */
    public static function badgeClass(string $status): string
    {
        return match ($status) {
            self::DRAFT => 'bg-secondary',
            self::PENDING => 'bg-warning',
            self::ACTIVE => 'bg-success',
            self::INACTIVE => 'bg-danger',
            self::CONFIRMED => 'bg-primary',
            self::COMPLETED => 'bg-success',
            self::CANCELLED => 'bg-danger',
            self::FAILED => 'bg-danger',
            self::ERROR => 'bg-danger',
            self::SUCCESS => 'bg-success',
            self::PAID => 'bg-success',
            self::UNPAID => 'bg-warning',
            self::PARTIAL => 'bg-info',
            self::REFUNDED => 'bg-secondary',
            self::ARCHIVED => 'bg-dark',

            self::CREATED => 'bg-success',
            self::UPDATED => 'bg-primary',
            self::DELETED => 'bg-danger',

            default => 'bg-secondary',
        };
    }

    /*
    |--------------------------------------------------------------------------
    | ICONS
    |--------------------------------------------------------------------------
    */
    public static function icon(string $status): string
    {
        return match ($status) {
            self::DRAFT => 'bi-pencil',
            self::PENDING => 'bi-clock',
            self::ACTIVE => 'bi-check-circle',
            self::INACTIVE => 'bi-x-circle',
            self::CONFIRMED => 'bi-check-lg',
            self::COMPLETED => 'bi-check2-circle',
            self::CANCELLED => 'bi-x-lg',
            self::FAILED => 'bi-exclamation-triangle',
            self::ERROR => 'bi-bug',
            self::SUCCESS => 'bi-check-circle-fill',
            self::PAID => 'bi-cash',
            self::UNPAID => 'bi-cash-stack',
            self::PARTIAL => 'bi-pie-chart',
            self::REFUNDED => 'bi-arrow-return-left',
            self::ARCHIVED => 'bi-archive',

            self::CREATED => 'bi-plus-circle',
            self::UPDATED => 'bi-pencil-square',
            self::DELETED => 'bi-trash',

            default => 'bi-question-circle',
        };
    }

    /*
    |--------------------------------------------------------------------------
    | GROUPES
    |--------------------------------------------------------------------------
    */
    public static function isPositive(string $status): bool
    {
        return in_array($status, [
            self::ACTIVE,
            self::CONFIRMED,
            self::COMPLETED,
            self::SUCCESS,
            self::PAID,
        ]);
    }

    public static function isNegative(string $status): bool
    {
        return in_array($status, [
            self::INACTIVE,
            self::CANCELLED,
            self::FAILED,
            self::ERROR,
            self::UNPAID,
        ]);
    }

    public static function isPending(string $status): bool
    {
        return in_array($status, [
            self::DRAFT,
            self::PENDING,
            self::PARTIAL,
        ]);
    }

    /*
    |--------------------------------------------------------------------------
    | LISTES
    |--------------------------------------------------------------------------
    */
    public static function all(): array
    {
        return [
            self::DRAFT,
            self::PENDING,
            self::ACTIVE,
            self::INACTIVE,
            self::CONFIRMED,
            self::COMPLETED,
            self::CANCELLED,
            self::FAILED,
            self::ERROR,
            self::SUCCESS,
            self::PAID,
            self::UNPAID,
            self::PARTIAL,
            self::REFUNDED,
            self::ARCHIVED,
            self::CREATED,
            self::UPDATED,
            self::DELETED,
        ];
    }

    public static function select(): array
    {
        $options = [];

        foreach (self::all() as $status) {
            $options[$status] = self::label($status);
        }

        return $options;
    }
}