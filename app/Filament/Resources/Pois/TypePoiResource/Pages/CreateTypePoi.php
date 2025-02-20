<?php

namespace App\Filament\Resources\Pois\TypePoiResource\Pages;

use App\Filament\Resources\Pois\TypePoiResource;
use Filament\Resources\Pages\CreateRecord;

class CreateTypePoi extends CreateRecord
{
    protected static string $resource = TypePoiResource::class;

    protected function getHeaderActions(): array
    {
        return [

        ];
    }
}
