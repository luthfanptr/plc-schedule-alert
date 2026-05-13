<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PlcStatus extends Model
{
    use HasFactory;

    protected $table = 'plc_statuses';
    protected $fillable = [
        'plc_id',
        'plant',
        'line',
        'line_name',
        'component_name',
        'counter',
        'limit',
        'status',
        'plc_date',
        'spk_status',
        'updated_by',
        'created_at',
        'updated_at',
    ];

    protected $casts = [
        'plc_id' => 'integer',
        'line' => 'integer',
        'counter' => 'integer',
        'limit' => 'integer',
        'plc_date' => 'datetime',
    ];

    public function users(){
        return $this->belongsTo(User::class, 'updated_by');
    }

    public function components(){
        return $this->hasMany(self::class, 'plc_id', 'plc_id');
    }
}
