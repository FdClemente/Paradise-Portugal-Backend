<?php

namespace App\Filament\Pages;

use App\Filament\Actions\TranslateAction;
use App\Filament\Actions\TranslateTextsAction;
use App\Settings\LegalSettings;
use Filament\Forms;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Pages\SettingsPage;
use Illuminate\Contracts\View\View;
use SolutionForest\FilamentTranslateField\Forms\Component\Translate;

class ManageLegalTexts extends SettingsPage
{
    protected static ?string $navigationIcon = 'heroicon-o-scale';
    protected static ?int $navigationSort = 10;

    protected static string $settings = LegalSettings::class;

    public function getHeaderActions(): array
    {
        return [
            TranslateTextsAction::make()->setSettingsModel(self::$settings)
        ];
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([Forms\Components\Section::make('Legal Texts')
                ->schema([
                    Translate::make()
                        ->prefixLocaleLabel()
                        ->contained(false)
                        ->columnSpanFull()
                        ->schema([
                            Forms\Components\RichEditor::make('terms_and_conditions'),
                            Forms\Components\RichEditor::make('privacy_policy'),
                            Forms\Components\RichEditor::make('cancellation_policy'),
                        ])
                ])
            ]);
    }
}
