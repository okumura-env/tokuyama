<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class OtherSchedule extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'other_schedules';

    protected $fillable = [
        'date_id',
        'vehicle_id',
        'schedule_id',
        'notes',
    ];
}
