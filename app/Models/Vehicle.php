<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Vehicle extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'code',
        'number',
        'capacity',
        'vehicle_type_id',
        'partner_id',
        'worker_id',
    ];

    /**
     * vehicle_typesテーブルとリレーション
     * 車両がどの車両タイプのものかを管理
     */
    public function vehicleType()
    {
        return $this->belongsTo(VehicleType::class);
    }

    /**
     * partnersテーブルとリレーション
     * 車両が所属する業者を管理
     */
    public function partner()
    {
        return $this->belongsTo(Partner::class);
    }

    /**
     * workersテーブルとリレーション
     * 車両に専属のドライバーを管理
     */
    public function worker()
    {
        return $this->belongsTo(Worker::class);
    }

    /**
     * dump_ordersテーブルとリレーション
     * 受注ごとに使用する車両を管理
     */
    public function dumpOrders()
    {
        return $this->hasMany(DumpOrder::class);
    }

    /**
     * dump_other_schedulesテーブルとリレーション
     * 予定に使用する車両を管理
     */
    public function dumpOtherSchedules()
    {
        return $this->hasMany(DumpOtherSchedule::class);
    }

    /**
     * dump_schedulesテーブルとリレーション
     * 予定に使用する車両を管理
     */
    public function dumpSchedules()
    {
        return $this->hasMany(DumpSchedule::class);
    }

    /**
     * datesテーブルとリレーション
     * 日毎の車両情報を管理する際の車両を管理
     */
    public function dates()
    {
        return $this->belongsToMany(Date::class)
                    ->using(DateVehicle::class) // ピボットモデルを指定
                    ->withPivot('id','work_type_id', 'worker_id', 'sub_worker', 'start_time', 'task_priority', 'driver_task_order', 'note')
                    ->withTimestamps();
    }

}
