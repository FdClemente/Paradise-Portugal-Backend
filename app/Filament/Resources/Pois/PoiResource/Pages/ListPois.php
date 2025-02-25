<?php

namespace App\Filament\Resources\Pois\PoiResource\Pages;

use App\Filament\Resources\Pois\PoiResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListPois extends ListRecords
{
    protected static string $resource = PoiResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
