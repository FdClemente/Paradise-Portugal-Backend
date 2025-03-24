<?php

namespace App\Filament\Resources\Pois\PoiResource\Pages;

use App\Filament\Actions\TranslateAction;
use App\Filament\Resources\Pois\PoiResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditPoi extends EditRecord
{
    protected static string $resource = PoiResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
            TranslateAction::make(),
        ];
    }
}
