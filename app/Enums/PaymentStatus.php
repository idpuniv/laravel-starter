<?php

namespace App\Enums;

enum PaymentStatus: string
{
    case PAID = 'paid';
    case UNPAID = 'unpaid';
    case PARTIAL = 'partial';
    case REFUNDED = 'refunded';

    public function label(): string
    {
        return __("statuses.payment.{$this->value}");
    }

    public function isPaid(): bool
    {
        return $this === self::PAID;
    }

    public function isUnpaid(): bool
    {
        return $this === self::UNPAID;
    }

    public function isRefunded(): bool
    {
        return $this === self::REFUNDED;
    }

    public function isPartial(): bool
    {
        return $this === self::PARTIAL;
    }
}