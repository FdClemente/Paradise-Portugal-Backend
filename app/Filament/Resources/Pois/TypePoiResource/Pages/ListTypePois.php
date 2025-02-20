<?php

namespace App\Filament\Resources\Pois\TypePoiResource\Pages;

use App\Filament\Resources\Pois\TypePoiResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListTypePois extends ListRecords
{
    protected static string $resource = TypePoiResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make()->slideOver(),
        ];
    }
}
