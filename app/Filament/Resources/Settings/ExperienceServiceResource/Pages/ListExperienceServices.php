<?php

namespace App\Filament\Resources\Settings\ExperienceServiceResource\Pages;

use App\Filament\Resources\Settings\ExperienceServiceResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListExperienceServices extends ListRecords
{
    protected static string $resource = ExperienceServiceResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make()->slideOver(),
        ];
    }
}
