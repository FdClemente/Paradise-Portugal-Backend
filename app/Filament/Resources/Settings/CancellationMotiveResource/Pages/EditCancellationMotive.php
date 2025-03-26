<?php

namespace App\Filament\Resources\Settings\CancellationMotiveResource\Pages;

use App\Filament\Resources\Settings\CancellationMotiveResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditCancellationMotive extends EditRecord
{
    protected static string $resource = CancellationMotiveResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
