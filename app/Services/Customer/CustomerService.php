<?php

namespace App\Services\Customer;

use App\Models\User;

class CustomerService
{
    public function createUser(array $details)
    {
        $user = User::create([
            'first_name' => $details['first_name'],
            'last_name' => $details['last_name'],
            'email_verified_at' => $details['email_verified_at'],
            'email' => $details['email'],
            'need_change_password' => $details['need_change_password'],
            'country_phone' => $details['country_phone'],
            'phone_number' => $details['phone_number'],
            'phone_number_verified_at' => $details['phone_number_verified_at'],
            'password' => $details['password'],
            'country' => $details['country'],
        ]);
        $user->save();

        return $user;
    }

    public function retrieveCustomer($email): ?User
    {
        return User::firstWhere('email', $email);
    }
}
