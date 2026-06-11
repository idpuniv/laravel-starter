<?php

namespace App\Enums;

enum PersonStatus: string
{
    case DRAFT = 'draft';
    case PENDING = 'pending';
    case ACTIVE = 'active';
    case INACTIVE = 'inactive';
    case BANNED = 'banned';

    public function label(): string
    {
        return __("statuses.person.{$this->value}");
    }

    public function isActive(): bool
    {
        return $this === self::ACTIVE;
    }

    public function isInactive(): bool
    {
        return $this === self::INACTIVE;
    }

    public function isBlocked(): bool
    {
        return $this === self::BANNED;
    }

    public function isPending(): bool
    {
        return $this === self::PENDING;
    }
}