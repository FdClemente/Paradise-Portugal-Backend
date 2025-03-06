<?php

namespace App\Http\Controllers\Api\Experiences;

use App\Enum\Dates\Months;
use App\Enum\Dates\Weekdays;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Experience\TicketsRequest;
use App\Http\Responses\Api\ApiSuccessResponse;
use App\Models\Experiences\Experience;
use App\Models\Experiences\ExperiencesAvailability;
use Carbon\Carbon;

class TicketsController extends Controller
{
    public function __invoke(Experience $experience, TicketsRequest $request)
    {
        $startDate = Carbon::parse($request->get('start_date'));
        $endDate = Carbon::parse($request->get('end_date'));


        $datesAvailable = $this->getAvailableDatesForExperience($experience, $startDate, $endDate);

        $tickets = [];

        foreach ($datesAvailable as $date) {
            $ticketTypes = [];

            foreach ($experience->tickets as $ticketType) {
                $ticketTypes[] = [
                    'day' => $date,
                    'date' => Carbon::parse($date)->format('l, d/m'),
                    'period' => $ticketType->type->getMobileLabel(),
                    'prices' => $ticketType->prices->map(function ($price) {
                        return [
                            'price' => $price->price,
                            'type' => $price->ticket_type->value,
                        ];
                    })
                ];
            }
            $tickets = array_merge($tickets, $ticketTypes);
        }

        return ApiSuccessResponse::make($tickets);
    }

    public function getAvailableDatesForExperience(Experience $experience, Carbon $startDate, Carbon $endDate)
    {
        $availabilities = $experience->availability()
            ->where('is_active', true)
            ->where('is_open', true)
            ->get();


        $availableDates = [];

        while ($startDate <= $endDate) {
            $month = Months::from(strtolower($startDate->monthName));
            $monthValid = in_array($month, $availabilities->pluck('month')->flatten()->toArray());

            $weekDay = Weekdays::from(strtolower($startDate->dayName));
            $weekdayValid = in_array($weekDay, $availabilities->pluck('weekday')->flatten()->toArray());

            $specificDateValid = $availabilities->pluck('specific_dates_formatted')->flatten()->contains($startDate->toDateString());

            if (($monthValid || $weekdayValid || $specificDateValid) && $availabilities->where('is_open', true)->count() > 0) {
                $availableDates[] = $startDate->toDateString();
            }

            $startDate->addDay();
        }

        return $availableDates;
    }

}
