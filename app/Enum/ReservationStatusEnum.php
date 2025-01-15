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
    //case AWAITING_REVIEW = 'awaiting_review';
}

