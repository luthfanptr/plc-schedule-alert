<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PlcStatusLog extends Model
{
    use HasFactory;

    public $timestamps = true;

    protected $fillable = [
        'plc_id',
        'plant',
        'line',
        'line_name',
        'component_name',
        'counter',
        'limit',
        'status',
        'spk_number',
        'spk_status',
        'spk_start_date',
        'spk_finish_date',
        'escalated',
        'plc_date',
        'resolved_at',
    ];

    protected $casts = [
        'spk_start_date'  => 'datetime',
        'spk_finish_date' => 'datetime',
        'plc_date'        => 'datetime',
        'resolved_at'     => 'datetime',
    ];
}
