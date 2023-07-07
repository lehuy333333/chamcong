<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class reports extends Model
{
    use HasFactory;

    public $timestamps = false;
    
    protected $fillable = [
        'employee_id', 
        'start_date', 
        'end_date',
        'total_timesheet',
        'total_base_workdate',
        'total_surplus_workdate',
        'total_overtime'
    ];
    
    public function employee(){
        return $this->belongsTo('App\Models\employees');
    }

}
