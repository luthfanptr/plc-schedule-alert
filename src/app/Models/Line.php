<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Line extends Model
{
    use HasFactory;
    protected $table = 'lines';
    public $timestamps = false;
    //protected $connection = 'sqlsrv';


    protected $fillable = [
        'Line',
        'Name'
    ];

    protected $casts = [
        'Line' => 'integer',
    ];

    public function plants(){
        return $this->belongsTo(Plant::class);
    }

    public function plc_data(){
        return $this->hasMany(PlcData::class);
    }
}
