<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Rule extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'rules';

    protected $fillable = [
        'name',
        'day_of_week',
        'vehicle_id',
        'priority',
        'mcm_task_type_id',
    ];

    public function mcmTaskType()
    {
        return $this->belongsTo(McmTaskType::class, 'mcm_task_type_id');
    }
}
