<?php

namespace App\Filament\Resources\Settings\HouseDetailsHighlightResource\Pages;

use App\Filament\Resources\Settings\HouseDetailsHighlightResource;
use Filament\Resources\Pages\CreateRecord;

class CreateHouseDetailsHighlight extends CreateRecord
{
    protected static string $resource = HouseDetailsHighlightResource::class;

    protected function getHeaderActions(): array
    {
        return [

        ];
    }
}
