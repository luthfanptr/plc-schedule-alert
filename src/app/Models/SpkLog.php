<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SpkLog extends Model
{
    use HasFactory;

    protected $table = 'spk_logs';
    protected $fillable = [
        'plc_id',
        'plant',
        'line',
        'line_name',
        'component_name',
        'counter',
        'limit',
        'updated_by',
        'status',
        'spk_status'
    ];

    protected $casts = [
        'plc_id',
        'line',
        'counter',
        'limit'
    ];

    public function updatedBy(){
        $this->belongsTo(User::class, 'updated_by');
    }
}
