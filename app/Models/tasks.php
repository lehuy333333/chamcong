<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class tasks extends Model
{
    use HasFactory;

    public $timestamps = false;
    
    protected $fillable = [
        'added_on', 
        'name', 
        'device_name',
        'description',
        'remedies',
        'started_at',
        'ended_at',
        'interruption_time',
        'interruption_cause',
        'type_repair',
        'result',
        'department_id'
    ];
    
    public function employees(){
        return $this->belongsToMany(employees::class, 'task_employee', 'task_id', 'employee_id');
    }

    public function department(){
        return $this->belongsTo(department::class);
    }

    public static function boot() {
        parent::boot();

        static::deleting(function($task) { // before delete() method call this
             $task->employees()->detach();
        });
    }
}
