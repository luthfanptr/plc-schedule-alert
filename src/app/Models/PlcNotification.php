<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PlcNotification extends Model
{
    use HasFactory;

    protected $table = 'plc_notifications';
    protected $fillable = [
        'plc_id',
        'plant',
        'line',
        'line_name',
        'component_name',
        'counter',
        'limit',
        'status',
        'spk_status',
        'updated_by',
    ];

    protected $casts = [
        'plc_id',
        'line',
        'counter',
        'limit',
    ];

    public function users(){
        return $this->belongsTo(User::class, 'updated_by');
    }
}
