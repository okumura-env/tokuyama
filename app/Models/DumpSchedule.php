<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class DumpSchedule extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'dump_schedules';

    protected $fillable = [
        'date_id',
        'vehicle_id',
        'dump_order_category_id',
        'dump_order_category_title_id',
        'dump_order_category_title',
        'schedule_type',
        'sort_order',
    ];
}
