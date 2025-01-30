<?php

namespace App\Providers;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\MorphToSelect;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TimePicker;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\ServiceProvider;
use Parfaitementweb\FilamentCountryField\Forms\Components\Country;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Select::configureUsing(function (Select $component): void {
            $component->native(false);
        });
        DatePicker::configureUsing(function (DatePicker $component): void {
            $component->native(false);
        });
        DateTimePicker::configureUsing(function (DateTimePicker $component): void {
            $component->native(false);
        });
        TimePicker::configureUsing(function (TimePicker $component): void {
            $component->native(false);
        });
        MorphToSelect::configureUsing(function (MorphToSelect $component): void {
            $component->native(false);
            $component->searchable();
            $component->preload();
        });

        Country::configureUsing(function (Country $component): void {
            $component->native(false);
            $component->searchable();
            $component->preload();
        });

        if ($this->app->environment('local') && class_exists(\Laravel\Telescope\TelescopeServiceProvider::class)) {
            $this->app->register(\Laravel\Telescope\TelescopeServiceProvider::class);
            $this->app->register(TelescopeServiceProvider::class);
        }

        if ($this->app->environment('production')) {
            URL::forceScheme('https');
        }
    }
}
