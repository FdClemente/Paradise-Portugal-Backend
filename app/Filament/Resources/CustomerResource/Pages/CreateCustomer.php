<?php

namespace App\Filament\Resources\CustomerResource\Pages;

use App\Filament\Resources\CustomerResource;
use App\Models\User;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Password;

class CreateCustomer extends CreateRecord
{
    protected static string $resource = CustomerResource::class;

    protected function getHeaderActions(): array
    {
        return [

        ];
    }

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['password'] = \Str::random(10);
        return $data;
    }

    protected function handleRecordCreation(array $data): Model
    {
        $user = User::create($data);

        $user->roles()->updateOrCreate(['role' => 'customer']);

        Password::sendResetLink($user->email);

        return $user;
    }
}
