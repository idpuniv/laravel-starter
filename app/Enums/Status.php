<?php

namespace App\Enums;

enum Status: string
{
    case DRAFT      = 'draft';        // Brouillon
    case PENDING    = 'pending';      // En attente
    case ACTIVE     = 'active';       // Actif
    case INACTIVE   = 'inactive';     // Inactif
    case CONFIRMED  = 'confirmed';    // Confirmé
    case COMPLETED  = 'completed';    // Terminé
    case CANCELLED  = 'cancelled';    // Annulé
    case FAILED     = 'failed';       // Échec
    case SUCCESS    = 'success';      // Succès
    case PAID       = 'paid';         // Payé
    case UNPAID     = 'unpaid';       // Non payé
    case PARTIAL    = 'partial';      // Partiel
    case REFUNDED   = 'refunded';     // Remboursé
    case ARCHIVED   = 'archived';     // Archivé

    public function label(): string
    {
        return match($this) {
            self::DRAFT => 'Brouillon',
            self::PENDING => 'En attente',
            self::ACTIVE => 'Actif',
            self::INACTIVE => 'Inactif',
            self::CONFIRMED => 'Confirmé',
            self::COMPLETED => 'Terminé',
            self::CANCELLED => 'Annulé',
            self::FAILED => 'Échec',
            self::SUCCESS => 'Succès',
            self::PAID => 'Payé',
            self::UNPAID => 'Non payé',
            self::PARTIAL => 'Partiel',
            self::REFUNDED => 'Remboursé',
            self::ARCHIVED => 'Archivé',
        };
    }

    public function badgeClass(): string
    {
        return match($this) {
            self::DRAFT => 'bg-secondary',
            self::PENDING => 'bg-warning',
            self::ACTIVE => 'bg-success',
            self::INACTIVE => 'bg-danger',
            self::CONFIRMED => 'bg-primary',
            self::COMPLETED => 'bg-success',
            self::CANCELLED => 'bg-danger',
            self::FAILED => 'bg-danger',
            self::SUCCESS => 'bg-success',
            self::PAID => 'bg-success',
            self::UNPAID => 'bg-warning',
            self::PARTIAL => 'bg-info',
            self::REFUNDED => 'bg-secondary',
            self::ARCHIVED => 'bg-dark',
        };
    }

    public function icon(): string
    {
        return match($this) {
            self::DRAFT => 'bi-pencil',
            self::PENDING => 'bi-clock',
            self::ACTIVE => 'bi-check-circle',
            self::INACTIVE => 'bi-x-circle',
            self::CONFIRMED => 'bi-check-lg',
            self::COMPLETED => 'bi-check2-circle',
            self::CANCELLED => 'bi-x-lg',
            self::FAILED => 'bi-exclamation-triangle',
            self::SUCCESS => 'bi-check-circle-fill',
            self::PAID => 'bi-cash',
            self::UNPAID => 'bi-cash-stack',
            self::PARTIAL => 'bi-pie-chart',
            self::REFUNDED => 'bi-arrow-return-left',
            self::ARCHIVED => 'bi-archive',
        };
    }
}