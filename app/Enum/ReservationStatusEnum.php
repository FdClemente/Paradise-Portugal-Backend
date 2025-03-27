<?php

namespace App\Enum;

enum ReservationStatusEnum: string
{
    case PENDING = 'pending';
    case CONFIRMED = 'confirmed';
    case PAID = 'paid';
    case CANCELED_BY_CLIENT = 'canceled_by_client';
    case CANCELED_BY_OWNER = 'canceled_by_owner';
    case CUSTOMER_WANT_CANCEL = 'customer_want_cancel';
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
            self::PENDING => 'warning',
            self::CONFIRMED => 'success',
            self::PAID => 'info',
            self::CANCELED_BY_CLIENT => 'danger',
            self::CANCELED_BY_OWNER => 'danger',
            self::CUSTOMER_WANT_CANCEL => 'danger',
            self::NO_SHOW => 'danger',
            self::IN_PROGRESS => 'info',
            self::COMPLETED => 'success',
            self::REJECTED => 'danger',
            self::REFUNDED => 'danger',
            self::PENDING_PAYMENT => 'warning',
        };
    }

    public static function getActiveReservations()
    {
        return [
            self::PENDING,
            self::CONFIRMED,
            self::PAID,
            self::IN_PROGRESS,
            self::CUSTOMER_WANT_CANCEL,
            self::COMPLETED,
        ];
    }
}

