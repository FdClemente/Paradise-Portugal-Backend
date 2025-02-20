<?php

namespace App\Filament\Resources\Pois\TypePoiResource\Pages;

use App\Filament\Resources\Pois\TypePoiResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditTypePoi extends EditRecord
{
    protected static string $resource = TypePoiResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
