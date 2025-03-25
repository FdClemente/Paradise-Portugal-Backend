<?php

namespace App\Filament\Resources\CancellationMotiveResource\Pages;

use App\Filament\Resources\CancellationMotiveResource;
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
