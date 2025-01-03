<?php

namespace App\Filament\Resources\Settings\HouseTypeResource\Pages;

use App\Filament\Resources\Settings\HouseTypeResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListHouseTypes extends ListRecords
{
    protected static string $resource = HouseTypeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make()->slideOver(),
        ];
    }
}
