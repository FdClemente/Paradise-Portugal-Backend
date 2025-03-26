<?php

namespace App\Filament\Resources\Settings\CancellationMotiveResource\Pages;

use App\Filament\Resources\Settings\CancellationMotiveResource;
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
