<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Worker extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'note',
    ];

    /**
     * vehiclesテーブルとリレーション
     * 車両専属の従業員を管理
     */
    public function vehicles()
    {
        return $this->hasMany(Vehicle::class);
    }

    /**
     * daily_vehicle_assignmentsテーブルとリレーション
     * 日毎の車両情報を管理する際の担当従業員を管理
     */
    public function dailyVehicleAssignments()
    {
        return $this->hasMany(DailyVehicleAssignment::class);
    }

}
