<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class JetpackOrder extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'jetpack_orders';

    protected $fillable = [
        'date_id',
        'vehicle_id',
        'jetpack_schedule_id',
        'jetpack_destination_route_id',
        'quantity',
        'status',
        'note',
    ];
}
