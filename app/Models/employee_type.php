<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class employee_type extends Model
{
    use HasFactory;

    public $timestamps = false;
    
    protected $fillable = [
        'Etype_name', 
    ];

    public function employees(){
        return $this->hasMany('App\Models\employees','Etype_id','id');
    }
}
