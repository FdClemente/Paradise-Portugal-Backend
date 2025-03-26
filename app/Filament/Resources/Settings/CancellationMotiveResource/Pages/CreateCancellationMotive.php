<?php

namespace App\Filament\Resources\Settings\CancellationMotiveResource\Pages;

use App\Filament\Resources\Settings\CancellationMotiveResource;
use Filament\Resources\Pages\CreateRecord;

class CreateCancellationMotive extends CreateRecord
{
    protected static string $resource = CancellationMotiveResource::class;

    protected function getHeaderActions(): array
    {
        return [

        ];
    }
}
