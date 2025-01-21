<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class JetpackDestination extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'jetpack_destinations';

    protected $fillable = [
        'name',
    ];
}
