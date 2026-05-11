<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Plant extends Model
{
    use HasFactory;
    protected $table = 'plants'; 
    public $timestamps = false;

    protected $fillable = [
        'Name',
    ];

    // relasi ke table User lewat pivot table UserPlant
    public function users(){
        return $this->belongsToMany(User::class, 'user_plants');
    }

    public function lines(){
        return $this->hasMany(Line::class);
    }
}
