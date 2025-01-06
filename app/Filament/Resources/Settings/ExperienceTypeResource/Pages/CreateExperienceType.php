<?php

namespace App\Filament\Resources\Settings\ExperienceTypeResource\Pages;

use App\Filament\Resources\Settings\ExperienceTypeResource;
use Filament\Resources\Pages\CreateRecord;

class CreateExperienceType extends CreateRecord
{
    protected static string $resource = ExperienceTypeResource::class;

    protected function getHeaderActions(): array
    {
        return [

        ];
    }
}
