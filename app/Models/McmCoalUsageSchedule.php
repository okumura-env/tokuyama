<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class McmCoalUsageSchedule extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'mcm_coal_usage_schedules';

    protected $fillable = [
        'date_id',
        'planned_amount',
        'usage_amount',
        'temporary_amount',
        'note',
    ];
}
