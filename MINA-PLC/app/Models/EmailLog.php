<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmailLog extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $table = 'email_logs';

    protected $fillable = [
        'plant',
        'date_sent',
    ];

    protected $casts = [
        'date_sent' => 'datetime',
    ];
}
