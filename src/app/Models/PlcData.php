<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PlcData extends Model
{
    use HasFactory;

    public $timestamps = false;
    protected $table = 'plc_data';
    protected $fillable = [
        'plc_id',
        'mem_id',
        'plant',
        'line',
        'line_name',
        'mem_data_name',
        'counter_limit',
        'data_value',
        'plc_date',
    ];

    protected $casts = [
        'plc_id' => 'integer',
        'mem_id' => 'integer',
        'line' => 'integer',
        'data_value' => 'integer',
        'plc_date' => 'datetime',
    ];

    public function lines(){
        return $this->belongsTo(Line::class);
    }
}
