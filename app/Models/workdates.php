<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class workdates extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = [
        'workdate', 
        'work_coefficient',
        'holiday',
        'isHoliday',
        'isWeekend',
        'isLock',
    ];

    public function timesheets(){
        return $this->hasMany('App\Models\timesheets','workdate_id','id');
    }
    public function employees()
    {
        return $this->belongsToMany(employees::class, 'timesheets', 'employee_id', 'id');
    }

}
