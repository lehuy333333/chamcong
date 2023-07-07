<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class work_symbols extends Model
{
    use HasFactory;

    public $timestamps = false;
    
    protected $fillable = [
        'symbol_name', 
        'symbol_id',
        'description',
        'work_symbol_coefficient',
    ];

    public function timesheets(){
        return $this->hasMany('App\Models\timesheets','work_symbol_id','id');
    }
}
