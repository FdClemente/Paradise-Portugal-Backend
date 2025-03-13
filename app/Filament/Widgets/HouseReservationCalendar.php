<?php

namespace App\Filament\Widgets;

use App\Filament\Resources\ReservationResource;
use App\Models\Reservation;
use Filament\Actions\Action;
use Filament\Actions\CreateAction;
use Filament\Actions\ViewAction;
use Filament\Forms\Form;
use Filament\Widgets\Widget;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Saade\FilamentFullCalendar\Widgets\FullCalendarWidget;

class HouseReservationCalendar extends FullCalendarWidget
{

    public string|int|null|Model $record= null;


    public function config(): array
    {
        return [
            'firstDay' => 1,
            'headerToolbar' => [
                'left' => 'dayGridMonth,dayGridWeek',
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
            $title = $event->customer?->name ?? 'Reservation';

            if ($event->customer && $event->house_id) {
                $title .= ' - ' . $event->house->name;
            }

            return [
                'title' => $title,
                'start' => $event->check_in_date,
                'end' => $event->check_out_date->addDay(),
                'url' => ReservationResource::getUrl(name: 'view', parameters: ['record' => $event]),
                'allDay' => true,
            ];
        })->all();

    }

    protected function headerActions(): array
    {
        return [
        ];
    }

    public function form(Form $form): Form
    {
        return  $form;
    }

    protected function viewAction(): Action
    {
        return ViewAction::make()
            ->infolist([

            ]);
    }

    public function onEventClick(array $info = [], ?string $action = null): void
    {

        // do something on click
        // $info contains the event data:
        // $info['event'] - the event object
        // $info['view'] - the view object
    }

}
