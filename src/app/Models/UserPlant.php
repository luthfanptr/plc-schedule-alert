<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserPlant extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'plant_id',
    ];

    protected $casts = [
        'user_id' => 'integer',
        'plant_id' => 'integer',
    ];
}
