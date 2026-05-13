<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PlcData extends Model
{
    use HasFactory;

    protected $table = 'plc_data';
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

    public function lines(){
        return $this->belongsTo(Line::class);
    }
}
