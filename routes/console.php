<?php

use App\Console\Commands\Reservation\SetReservationAsInProgressCommand;
use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;

/*Schedule::command('reservation:start')
    ->daily()
    ->at('07:00');*/

Schedule::command('import:houses')
    ->daily()
    ->at('00:00');
Schedule::command('reservation:send-reminder')
    ->daily()
    ->at('10:00');

Schedule::command('reservation:finish')
    ->daily()
    ->at('16:00');

Schedule::command('refresh:cache')
    ->hourly();
