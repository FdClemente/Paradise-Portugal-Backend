<?php

namespace App\Filament\Resources\ExperienceResource\Pages;

use App\Filament\Actions\TranslateAction;
use App\Filament\Resources\ExperienceResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\ForceDeleteAction;
use Filament\Actions\RestoreAction;
use Filament\Resources\Pages\EditRecord;

class EditExperience extends EditRecord
{
    protected static string $resource = ExperienceResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
            TranslateAction::make(),
            ForceDeleteAction::make(),
            RestoreAction::make(),
        ];
    }
}
