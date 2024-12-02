<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class DailyVehicleAssignment extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'daily_vehicle_assignments';

    protected $fillable = [
        'date_id',
        'work_type_id',
        'vehicle_id',
        'worker_id',
        'sub_worker',
        'start_time',
        'task_priority',
        'driver_task_order',
        'notes',
    ];
}
