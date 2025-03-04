<?php

namespace App\Filament\Resources\Settings\HouseDetailsHighlightResource\Pages;

use App\Filament\Resources\Settings\HouseDetailsHighlightResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditHouseDetailsHighlight extends EditRecord
{
    protected static string $resource = HouseDetailsHighlightResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
