<?php

namespace App\Models\Auth;

use App\Enum\LoginProviders;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;

class LoginProvider extends Model
{
    protected  $table = 'oauth_tokens';

    protected $casts = [
        'provider' => LoginProviders::class
    ];

    protected $fillable = [
        'user_id',
        'provider',
        'provider_id'
    ];

    public function user(){
        return $this->belongsTo(User::class);
    }
}
