<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class McmTaskType extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'mcm_task_types';

    protected $fillable = [
        'name',
    ];
}
