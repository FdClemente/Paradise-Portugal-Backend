<?php

namespace App\Filament\Actions;

use App\Jobs\TranslateJob;
use App\Jobs\TranslateSettingsJob;
use Filament\Actions\Action;
use Filament\Forms\Components\Select;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;
use Spatie\Translatable\Translatable;

class TranslateTextsAction extends Action
{
    private $settings;
    public function setUp(): void
    {
        parent::setUp();

        $this->label('Translate');
        $this->icon('heroicon-o-language');
        $this->successNotificationTitle("Translated successfully");
        $this->color('info');
        $this->form(function () {
            $settingField = get_class_vars($this->settings);

            $attributes = [];
            foreach ($settingField as $key => $field){
                $attributes[] = $key;
            }

            $attributes = array_combine($attributes, $attributes);


            return [
                Select::make('attributes')
                    ->label(__('filament.translate.attributes'))
                    ->multiple()
                    ->default($attributes)
                    ->options($attributes),
                Select::make('originLanguage')
                    ->label(__('filament.translate.originLanguage'))
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
                    ->label(__('filament.translate.targetLanguage'))
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

        $this->action(function ($form, $data) {
            $settings = app($this->settings);

            TranslateSettingsJob::dispatchSync($settings, $data['originLanguage'], $data['attributes'], $data['targetLanguage']);
            $this->sendSuccessNotification();
            $form->fill($data);
        });
    }

    public static function make($name = 'translate'): static
    {
        return parent::make($name);
    }

    public function setSettingsModel($settings)
    {
        $this->settings = $settings;
        return $this;
    }
}
