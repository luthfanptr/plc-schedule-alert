<?php

namespace App\Models;

use Laravel\Sanctum\PersonalAccessToken as SanctumToken;

class PersonalAccessToken extends SanctumToken
{
    protected $fillable = [
        'name', 
        'token',
        'abilities',
        'encrypted_plain_token',
    ];

    protected $casts = [
        'abilities' => 'json',
        'expires_at' => 'datetime',
        'last_used_at' => 'datetime',
        'encrypted_plain_token' => 'encrypted',
    ];
}
