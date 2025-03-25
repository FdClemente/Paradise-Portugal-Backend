<?php

namespace App\Filament\Resources\CancellationMotiveResource\Pages;

use App\Filament\Resources\CancellationMotiveResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListCancellationMotives extends ListRecords
{
    protected static string $resource = CancellationMotiveResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make()->slideOver(),
        ];
    }
}
