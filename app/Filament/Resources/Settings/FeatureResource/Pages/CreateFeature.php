<?php

namespace App\Filament\Resources\Settings\FeatureResource\Pages;

use App\Filament\Resources\Settings\FeatureResource;
use Filament\Resources\Pages\CreateRecord;

class CreateFeature extends CreateRecord
{
    protected static string $resource = FeatureResource::class;

    protected function getHeaderActions(): array
    {
        return [

        ];
    }
}
