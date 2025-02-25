<?php

namespace App\Filament\Resources\Pois\PoiResource\Pages;

use App\Filament\Resources\Pois\PoiResource;
use Filament\Resources\Pages\CreateRecord;

class CreatePoi extends CreateRecord
{
    protected static string $resource = PoiResource::class;

    protected function getHeaderActions(): array
    {
        return [

        ];
    }
}
