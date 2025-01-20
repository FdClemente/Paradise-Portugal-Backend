<?php

namespace App\Filament\Widgets;

use App\Filament\Resources\ReservationResource;
use App\Models\Reservation;
use Filament\Actions\CreateAction;
use Filament\Forms\Form;
use Filament\Widgets\Widget;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Modules\Staff\Models\ClassGroup;
use Saade\FilamentFullCalendar\Widgets\FullCalendarWidget;

class HouseReservationCalendar extends FullCalendarWidget
{

    public string|int|null|Model $record= null;

    protected bool $eventClickEnabled = false;
    protected bool $eventDragEnabled = false;
    public function config(): array
    {
        return [
            'firstDay' => 1,
            'headerToolbar' => [
                'left' => 'dayGridWeek,dayGridDay',
                'center' => 'title',
                'right' => 'prev,next today',
            ],
        ];
    }


    public function fetchEvents(array $fetchInfo = []): array
    {
        $query = Reservation::query();

        if ($this->record) {
            $query->where('house_id', $this->record->id);
        }

        $query->where(function ($query) use ($fetchInfo) {
            $query->whereBetween('check_in_date', [$fetchInfo['start'], $fetchInfo['end']])
                ->orWhereBetween('check_out_date', [$fetchInfo['start'], $fetchInfo['end']]);
        });

        $events = $query->get();

        return $events->map(function ($event) {
            return [
                'title' => $event->customer->name,
                'start' => $event->check_in_date,
                'end' => $event->check_out_date,
            ];
        })->all();
    }

    protected function headerActions(): array
    {
        return [
            CreateAction::make()
            ->form(function (Form $form) {
                return ReservationResource::form($form);
            })
                /*->mountUsing(
                    function (Form $form, array $arguments) {
                        $form->fill([
                            'starts_at' => $arguments['start'] ?? null,
                            'ends_at' => $arguments['end'] ?? null
                        ]);
                    }
                )*/
        ];
    }

    /*public function onEventClick(array $info = [], ?string $action = null): void
    {
        dd($info);
        // do something on click
        // $info contains the event data:
        // $info['event'] - the event object
        // $info['view'] - the view object
    }*/

}
