<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use NotificationChannels\Expo\ExpoPushToken;

class Device extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'expo_token',
        'user_id',
        'device_name',
    ];

    protected $casts = [
        'expo_token' => ExpoPushToken::class,
    ];
}
