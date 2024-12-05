<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Date extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'date',
        'jetpack_note',
    ];

    /**
     * dump_ordersテーブルとリレーション
     * 紐づく受注案件の日付を管理
     */
    public function dumpOrders()
    {
        return $this->hasMany(DumpOrder::class);
    }

    /**
     * dump_other_schedulesテーブルとリレーション
     * その他の予定の日付を管理
     */
    public function dumpOtherSchedules()
    {
        return $this->hasMany(DumpOtherSchedule::class);
    }

    /**
     * dump_schedulesテーブルとリレーション
     * 受注とその他の予定の日付を管理
     */
    public function dumpSchedules()
    {
        return $this->hasMany(DumpSchedule::class);
    }

    /**
     * daily_vehicle_assignmentsテーブルとリレーション
     * 日毎の車両情報を管理する際の日付を管理
     */
    public function dailyVehicleAssignments()
    {
        return $this->hasMany(DailyVehicleAssignment::class);
    }

}
