<?php

namespace App\Models;

use Laravel\Sanctum\PersonalAccessToken as SanctumToken;

class PersonalAccessToken extends SanctumToken
{
    protected $fillable = [
        'name', 
        'token',
        'abilities',
        'description',
        'is_shared',
        'plain_token',
    ];

    protected $casts = [
        'abilities' => 'json',
        'is_shared' => 'boolean',
    ];
}
