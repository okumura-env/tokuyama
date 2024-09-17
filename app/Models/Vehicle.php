<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Vehicle extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'partner_id',
    ];

    /**
     * 受注テーブルとリレーション
     */

     public function orders()
     {
         return $this->hasMany(Order::class);
     }

    /**
     * 業者テーブルとリレーション
     */
    public function partner()
    {
        return $this->belongsTo(Partner::class);
    }
}
