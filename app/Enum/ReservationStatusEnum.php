<?php

namespace App\Enum;

enum ReservationStatusEnum: string
{
    case PENDING = 'pending';
    case CONFIRMED = 'confirmed';
    case PAID = 'paid';
    case CANCELED_BY_CLIENT = 'canceled_by_client';
    case CANCELED_BY_OWNER = 'canceled_by_owner';
    case NO_SHOW = 'no_show';
    case IN_PROGRESS = 'in_progress';
    case COMPLETED = 'completed';
    case REJECTED = 'rejected';
    case REFUNDED = 'refunded';

    case PENDING_PAYMENT = 'pending_payment';
    //case AWAITING_REVIEW = 'awaiting_review';

    public function getColor(): string
    {
        return match ($this) {
            self::PENDING => 'yellow',
            self::CONFIRMED => 'green',
            self::PAID => 'blue',
            self::CANCELED_BY_CLIENT => 'red',
            self::CANCELED_BY_OWNER => 'red',
            self::NO_SHOW => 'red',
            self::IN_PROGRESS => 'blue',
            self::COMPLETED => 'green',
            self::REJECTED => 'red',
            self::REFUNDED => 'red',
            self::PENDING_PAYMENT => 'yellow',
        };
    }

    public static function getActiveReservations()
    {
        return [
            self::PENDING,
            self::CONFIRMED,
            self::PAID,
            self::IN_PROGRESS,
            self::COMPLETED,
        ];
    }
}

