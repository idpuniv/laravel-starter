<?php

namespace App\Enums;

enum UserStatus: string
{
    case ACTIVE = 'active';
    case INACTIVE = 'inactive';
    case BANNED = 'banned';
    case PENDING = 'pending';

    /*
    |--------------------------------------------------------------------------
    | LABEL (MULTI-LANGUE)
    |--------------------------------------------------------------------------
    */
    public function label(): string
    {
        return __("statuses.user.{$this->value}");
    }

    /*
    |--------------------------------------------------------------------------
    | HELPERS
    |--------------------------------------------------------------------------
    */
    public function isActive(): bool
    {
        return $this === self::ACTIVE;
    }

    public function isInactive(): bool
    {
        return $this === self::INACTIVE;
    }

    public function isBanned(): bool
    {
        return $this === self::BANNED;
    }

    public function isPending(): bool
    {
        return $this === self::PENDING;
    }
}