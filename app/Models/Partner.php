<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Partner extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'color',
    ];

    /**
     * vehiclesテーブルとリレーション
     * 車両が所属する業者を管理
     */
    public function vehicles()
    {
        return $this->hasMany(Vehicle::class);
    }
}
