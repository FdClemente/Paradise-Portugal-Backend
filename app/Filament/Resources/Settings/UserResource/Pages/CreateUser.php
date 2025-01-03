<?php

namespace App\Filament\Resources\Settings\UserResource\Pages;

use App\Filament\Resources\Settings\UserResource;
use App\Models\User;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Database\Eloquent\Model;

class CreateUser extends CreateRecord
{
    protected static string $resource = UserResource::class;

    protected function getHeaderActions(): array
    {
        return [

        ];
    }

    protected function handleRecordCreation(array $data): Model
    {
        $user = User::create($data);

        $user->roles()->updateOrCreate(['role' => 'admin']);

        return $user;
    }
}
