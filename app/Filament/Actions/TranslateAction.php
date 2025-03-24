<?php

namespace App\Filament\Actions;

use App\Jobs\TranslateJob;
use Filament\Actions\Action;
use Filament\Forms\Components\Select;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;
use Spatie\Translatable\Translatable;

class TranslateAction extends Action
{
    public function setUp(): void
    {
        parent::setUp();

        $this->label('Translate');
        $this->icon('heroicon-o-language');
        $this->successNotificationTitle("Translated successfully");
        $this->color('info');
        $this->form(function (Model $record) {
            if (!in_array(HasTranslations::class, class_uses_recursive($record))) {
                throw new \LogicException('Model does not use Translatable trait');
            }

            $options = $record->translatable;

            $options = array_combine($options, $options);

            return [
                Select::make('attributes')
                    ->multiple()
                    ->default($record->translatable)
                    ->options($options),
                Select::make('originLanguage')
                    ->live()
                    ->options(config('app.available_locales_with_labels'))
                    ->live()
                    ->afterStateUpdated(function (Set $set, Get $get, $state) {
                        $options = array_filter(config('app.available_locales_with_labels'), function ($key) use ($state) {
                            return $key !== $state;
                        }, ARRAY_FILTER_USE_KEY);

                        $set('targetLanguage', array_keys($options));
                    })
                    ->default('en'),
                Select::make('targetLanguage')
                    ->reactive()
                    ->options(function (Get $get){
                        $originLanguage = $get('originLanguage');
                        $options = array_filter(config('app.available_locales_with_labels'), function ($key) use ($originLanguage) {
                            return $key !== $originLanguage;
                        }, ARRAY_FILTER_USE_KEY);
                        return $options;
                    })
                    ->default(function (Get $get) {
                        $state = $get('originLanguage');
                        $options = array_filter(config('app.available_locales_with_labels'), function ($key) use ($state) {
                            return $key !== $state;
                        }, ARRAY_FILTER_USE_KEY);

                        return array_keys($options);
                    })
                    ->multiple()
            ];
        });

        $this->action(function ($data, EditRecord $livewire) {
            TranslateJob::dispatchSync($this->record, $data['originLanguage'], $data['attributes'], $data['targetLanguage']);
            $this->sendSuccessNotification();
            $this->record->refresh();
            $data = $livewire->getRecord()->attributesToArray();
            $livewire->form->fill($data);
            return ;
        });
    }

    public static function make($name = 'translate'): static
    {
        return parent::make($name);
    }
}
