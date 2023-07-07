<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class timesheets extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = [
        'workdate_id', 
        'employee_id',
        'work_symbol_id',
        'overtime',
        'overtime_night',
        'work_coefficient',
        'explain',
        'duty'
    ];

    public function workdate(){
        return $this->belongsTo('App\Models\workdates');
    }

    public function employee(){
        return $this->belongsTo('App\Models\employees');
    }

    public function work_symbol(){
        return $this->belongsTo('App\Models\work_symbols');
    }

    public function Reports(){
        return $this->hasMany('App\Models\reports','timesheet_id','id');
    }
}
