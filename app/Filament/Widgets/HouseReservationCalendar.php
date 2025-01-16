<?php

namespace App\Filament\Widgets;

use App\Models\Reservation;
use Filament\Widgets\Widget;
use Guava\Calendar\Widgets\CalendarWidget;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

class HouseReservationCalendar extends CalendarWidget
{

    public ?Model $record = null;


    public function getEvents(array $fetchInfo = []): Collection | array
    {
        return Reservation::where('house_id', $this->record)
            ->whereBetween('check_in_date', [$fetchInfo['start'], $fetchInfo['end']])
            ->orWhereBetween('check_out_date', [$fetchInfo['start'], $fetchInfo['end']])
            ->get();
    }

    public function onEventClick(array $info = [], ?string $action = null): void
    {
        dd($info);
        // do something on click
        // $info contains the event data:
        // $info['event'] - the event object
        // $info['view'] - the view object
    }
}
