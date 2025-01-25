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
     * Jetpack_ordersテーブルとリレーション
     * 紐づく受注案件の日付を管理
     */
    public function JetpackOrders()
    {
        return $this->hasMany(JetpackOrder::class);
    }

    /**
     * jetpack_schedulesテーブルとリレーション
     * 受注とその他の予定の日付を管理
     */
    public function jetpackSchedules()
    {
        return $this->hasMany(JetpackSchedule::class);
    }

    /**
     * vehiclesテーブルとリレーション
     * 日毎の車両情報を管理する際の日付を管理
     */
    public function vehicles()
    {
        return $this->belongsToMany(Vehicle::class)
                    ->using(DateVehicle::class) // ピボットモデルを指定
                    ->withPivot('id','work_type_id', 'worker_id', 'sub_worker', 'start_time', 'task_priority', 'driver_task_order', 'note')
                    ->withTimestamps();
    }
}
