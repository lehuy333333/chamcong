<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class position extends Model
{
    use HasFactory;

    public $timestamps = false;
    
    
    protected $fillable = [
        'position_name', 
    ];

    public function employees(){
        return $this->hasMany('App\Models\employees','position_id','id');
    }
}
