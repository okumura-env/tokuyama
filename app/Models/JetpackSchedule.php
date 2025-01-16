<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class JetpackSchedule extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'jetpack_schedules';

    protected $fillable = [
        'date_id',
        'vehicle_id',
        'date_vehicle_id',
        'cell_number',
        'order_sequence',
    ];
}
