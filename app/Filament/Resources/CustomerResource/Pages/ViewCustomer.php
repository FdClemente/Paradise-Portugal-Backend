<?php

namespace App\Filament\Resources\CustomerResource\Pages;

use App\Filament\Resources\CustomerResource;
use App\Models\Experiences\Experience;
use App\Models\House\House;
use App\Models\User;
use App\Notifications\MarketingNotification;
use Filament\Actions;
use Filament\Forms\Components\Fieldset;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Get;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ViewRecord;

class ViewCustomer extends ViewRecord
{
    protected static string $resource = CustomerResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\Action::make('markEmailAsVerified')
                ->icon('heroicon-o-envelope')
                ->color('info')
                ->label(__('filament.user.mark_email_as_verified'))
                ->requiresConfirmation()
                ->hidden(fn($record): bool => $record->hasVerifiedEmail())
                ->action(function ($record) {
                    $record->markEmailAsVerified();
                    Notification::make()
                        ->title(__('filament.user.email_mark_as_verified'))
                        ->success()
                        ->send();
                }),
            Actions\Action::make('sendNotification')
                ->color('info')
                ->form([
                    TextInput::make('title')->required(),
                    TextInput::make('body')->required(),
                    Fieldset::make('Open')
                        ->schema([
                            Select::make('open_type')
                                ->reactive()
                                ->options([
                                    'House' => 'Houses',
                                    'Experience' => 'Experiences',
                                ]),
                            Select::make('open_id')->options(function (Get $get) {
                                return match ($get('open_type')) {
                                    'House' => House::all()->pluck('name', 'id'),
                                    'Experience' => Experience::all()->pluck('name', 'id'),
                                    default => []
                                };
                            })
                        ])
                ])
                ->action(function (User $record, $data) {
                    if (isset($data['open_id']) && isset($data['open_type'])){
                        $url = $data['open_type'].'/'.$data['open_id'];
                    }else{
                        $url = null;
                    }
                    $record->notify(new MarketingNotification($data['title'], $data['body'], $url));
                }),
            Actions\EditAction::make(),
        ];
    }
}
