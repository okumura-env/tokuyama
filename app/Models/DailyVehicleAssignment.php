<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class DailyVehicleAssignment extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'daily_vehicle_assignments';

    protected $fillable = [
        'date_id',
        'vehicle_id',
        'work_type_id',
        'worker_id',
        'sub_worker',
        'start_time',
        'task_priority',
        'driver_task_order',
        'notes',
    ];

    /**
     * datesテーブルとリレーション
     * 日毎の車両情報を管理する際の日付を管理
     */
    public function date()
    {
        return $this->belongsTo(Date::class);
    }

    /**
     * work_typesテーブルとリレーション
     * 日毎の車両情報を管理する際の「ジェットパック」「ダンプ」「WP・PKS業務」「その他」かを管理
     */
    public function workType()
    {
        return $this->belongsTo(WorkType::class);
    }

    /**
     * vehiclesテーブルとリレーション
     * 日毎の車両情報を管理する際の車両を管理
     */
    public function vehicle()
    {
        return $this->belongsTo(Vehicle::class);
    }

    /**
     * workersテーブルとリレーション
     * 日毎の車両情報を管理する際の担当ドライバーを管理
     */
    public function worker()
    {
        return $this->belongsTo(Worker::class);
    }

    /**
     * dump_ordersテーブルとリレーション
     * 日毎で統一の車両情報を管理
     */
    public function dumpOrders()
    {
        return $this->hasMany(DumpOrder::class);
    }

}
