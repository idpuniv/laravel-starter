<?php

namespace App\Enums;

enum CommentStatus: string
{
    case PENDING = 'pending';
    case APPROVED = 'approved';
    case SPAM = 'spam';

    /*
    |--------------------------------------------------------------------------
    | LABEL (MULTI-LANGUE)
    |--------------------------------------------------------------------------
    */
    public function label(): string
    {
        return __("statuses.comment.{$this->value}");
    }

    /*
    |--------------------------------------------------------------------------
    | HELPERS
    |--------------------------------------------------------------------------
    */
    public function isPending(): bool
    {
        return $this === self::PENDING;
    }

    public function isApproved(): bool
    {
        return $this === self::APPROVED;
    }

    public function isSpam(): bool
    {
        return $this === self::SPAM;
    }
}
