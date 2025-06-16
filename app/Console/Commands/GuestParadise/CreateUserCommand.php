<?php

namespace App\Console\Commands\GuestParadise;

use App\Models\User;
use Illuminate\Console\Command;

class CreateUserCommand extends Command
{
    protected $signature = 'guest-paradise:create-user';

    protected $description = 'Command description';

    public function handle(): void
    {
        if (!User::where('email', 'integration@guestparadise.com')->exists()){
            $user = new User();
            $user->first_name = 'GuestParadise';
            $user->last_name = 'Integration';
            $user->password = \Str::random(64);
            $user->email = 'integration@guestparadise.com';
            $user->save();
        }else{
            $user = User::where('email', 'integration@guestparadise.com')->first();
        }

        $token = $user->createToken('Integration', ['server:integration'])->plainTextToken;
        $this->info('Token: '.$token);
    }
}
