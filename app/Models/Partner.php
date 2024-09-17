<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Partner extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
    ];

    /**
     * 受注テーブルとリレーション
     */

     public function orders()
     {
         return $this->hasMany(Order::class);
     }

    /**
     * 車両テーブルとリレーション
     */
     public function vehicles()
     {
         return $this->hasMany(Vehicle::class);
     }
}
