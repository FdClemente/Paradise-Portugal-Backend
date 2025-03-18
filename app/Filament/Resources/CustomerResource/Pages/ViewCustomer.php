<?php

namespace App\Filament\Resources\CustomerResource\Pages;

use App\Filament\Resources\CustomerResource;
use App\Models\User;
use App\Notifications\MarketingNotification;
use Filament\Actions;
use Filament\Forms\Components\TextInput;
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
                ->action(function ($record){
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
                ])
            ->action(function (User $record, $data){
                $record->notify(new MarketingNotification($data['title'], $data['body']));
            }),
            Actions\EditAction::make(),
        ];
    }
}
