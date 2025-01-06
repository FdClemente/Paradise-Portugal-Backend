<?php

namespace App\Filament\Resources\Settings\ExperiencePartnerResource\Pages;

use App\Filament\Resources\Settings\ExperiencePartnerResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListExperiencePartners extends ListRecords
{
    protected static string $resource = ExperiencePartnerResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make()->slideOver(),
        ];
    }
}
