<?php

namespace App\Support;

use App\Enums\PersonStatus;
use App\Enums\PaymentStatus;
use App\Enums\UserStatus;

class StatusUI
{
   
    public static function icon(PersonStatus|PaymentStatus|UserStatus $status): string
    {
        return match (true) {

            $status instanceof PersonStatus => match ($status) {
                PersonStatus::DRAFT => 'bi-pencil',
                PersonStatus::PENDING => 'bi-clock',
                PersonStatus::ACTIVE => 'bi-check-circle',
                PersonStatus::INACTIVE => 'bi-x-circle',
                PersonStatus::BANNED => 'bi-shield-exclamation',
            },

            $status instanceof PaymentStatus => match ($status) {
                PaymentStatus::PAID => 'bi-cash-coin',
                PaymentStatus::UNPAID => 'bi-cash',
                PaymentStatus::PARTIAL => 'bi-pie-chart',
                PaymentStatus::REFUNDED => 'bi-arrow-return-left',
            },

            $status instanceof UserStatus => match ($status) {
                UserStatus::ACTIVE => 'bi-check-circle-fill text-success',
                UserStatus::INACTIVE => 'bi-pause-circle-fill text-warning',
                UserStatus::BANNED => 'bi-shield-exclamation-fill text-danger',
            },

            default => 'bi-question-circle',
        };
    }
}


// <i class="{{ \App\Support\StatusUI::icon($person->status) }}"></i>