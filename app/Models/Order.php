<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_date',
        'vehicle_id',
    ];

    /**
     * 車両テーブルとリレーション
     */
     public function vehicle()
     {
         return $this->belongsTo(Vehicle::class);
     }

    /**
     * 業者テーブルとリレーション
     */
     public function partner()
     {
         return $this->belongsTo(Partner::class);
     }


}
