<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class department extends Model
{
    use HasFactory;

    public $timestamps = false;
    
    protected $fillable = [
        'department_name', 
        'department_code',
    ];

    public function users(){
        return $this->hasMany('App\Models\User','department_id','id');
    }

    public function employees(){
        return $this->hasMany('App\Models\employees','department_id','id');
    }

    public function tassks(){
        return $this->hasMany('App\Models\tasks','department_id','id');
    }
}
