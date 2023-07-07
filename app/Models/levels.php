<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class levels extends Model
{
    use HasFactory;

    public $timestamps = false;
    
    
    protected $fillable = [
        'level_name', 
    ];

    
    public function users(){
        return $this->hasMany('App\Models\User','level_id','id');
    }

    public function employees(){
        return $this->hasMany('App\Models\employees','level_id','id');
    }


}
