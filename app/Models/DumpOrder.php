<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class DumpOrder extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'dump_orders';

    protected $fillable = [
        'date_id',
        'vehicle_id',
        'schedule_id',
        'daily_vehicle_assignment_id',
        'dump_order_category_id',
        'dump_order_category_title_id',
        'boiler_number',
        'status',
        'is_preloaded',
        'vehicle_number',
        'notes',
    ];
}
