<?php

namespace App\Filament\Resources\Settings\HouseDetailsHighlightResource\Pages;

use App\Filament\Resources\Settings\HouseDetailsHighlightResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListHouseDetailsHighlights extends ListRecords
{
    protected static string $resource = HouseDetailsHighlightResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make()->slideOver(),
        ];
    }
}
