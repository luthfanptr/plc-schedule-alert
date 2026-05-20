<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Override;

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
        'spk_number',
        'spk_start_date',
        'spk_finish_date',
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
        'spk_start_date' => 'datetime',
        'spk_finish_date' => 'datetime',
    ];

    // Mapping user yang melakukan update data SPK Status
    #[Override]
    protected static function booted()
    {
        static::updating(function (PlcStatus $model) {
            if(Auth::check()) {
                $model->updated_by = Auth::id();

                $model->unsetRelation('users');
            }
        });
    }

    public function users(){
        return $this->belongsTo(User::class, 'updated_by');
    }

    public function components(){
        return $this->hasMany(self::class, 'plc_id', 'plc_id');
    }
}
