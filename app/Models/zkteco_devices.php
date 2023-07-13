<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class zkteco_devices extends Model
{
    use HasFactory;

    protected $fillable = [
        'ip', 
        'port',
        'model_name',
        'status',
    ];

}
