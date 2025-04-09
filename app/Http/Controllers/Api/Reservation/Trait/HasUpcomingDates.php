<?php

namespace App\Http\Controllers\Api\Reservation\Trait;

use Carbon\Carbon;

trait HasUpcomingDates
{
    private function formatDate(?Carbon $date)
    {
        if ($date == null){
            return "";
        }

        if ($date->isToday()){
            return "Today";
        }

        if ($date->isTomorrow()){
            return "Tomorrow";
        }

        if ($date->diffInDays(now(), true) < 30) {
            $days = $date->diffInDays(now(), true);
            $days = intval($days);
            return trans_choice('reservation.diffInDays', $days, ['days' => $days]);
        } else {
            $months = $date->diffInMonths(now(), true);
            $months = intval($months);
            return trans_choice('reservation.diffInMonths', $months, ['months' => $months]);
        }

    }
}
