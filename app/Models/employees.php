<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class employees extends Model
{
    
        
    use HasFactory, SoftDeletes;
    public $timestamps = false;

    protected $fillable = [
        'firstname', 'email', 'lastname','employeeID',
        'department_id','position_id', 'employee_type_id',
        'deleted', 'personal_coefficient',

        // 'sex', 'birthday','address',
        

    ];

    public function position(){
        return $this->belongsTo('App\Models\position');
    }

    public function department(){
        return $this->belongsTo('App\Models\department');
    }

    public function employee_type(){
        return $this->belongsTo('App\Models\employee_type');
    }

    public function timesheets(){
        return $this->hasMany('App\Models\timesheets','employee_id','id');
    }

    public function timesheetsByMonth($start, $end){
        return $this->hasMany('App\Models\timesheets','employee_id','id')->whereBetween('workdate_id', [$start, $end]);
    }

    public function workdates()
    {
        return $this->belongsToMany(workdates::class, 'timesheets', 'workdate_id', 'id');
    }

    public function reports(){
        return $this->hasMany('App\Models\reports','employee_id','id');
    }

    public function tasks()
    {
        return $this->belongsToMany(tasks::class, 'task_employee', 'employee_id', 'task_id');
    }

}
