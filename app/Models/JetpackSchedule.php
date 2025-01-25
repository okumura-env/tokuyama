<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class JetpackSchedule extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'jetpack_schedules';

    protected $fillable = [
        'date_id',
        'vehicle_id',
        'date_vehicle_id',
        'cell_number',
        'order_sequence',
    ];

    /**
     * datesテーブルとリレーション
     * 予定の日付を管理
     */
    public function date()
    {
        return $this->belongsTo(Date::class);
    }

    /**
     * vehiclesテーブルとリレーション
     * 予定に使用する車両を管理
     */
    public function vehicle()
    {
        return $this->belongsTo(Vehicle::class);
    }

    /**
     * date_vehicleテーブルとリレーション
     * 同一日付・車両の組み合わせでのジェットパックスケジュールを管理
     */
    public function dateVehicle()
    {
        return $this->belongsTo(DateVehicle::class);
    }

    /**
     * jetpack_ordersテーブルとリレーション
     * 受注を含めた予定の大分類を管理
     * 一対一のリレーション
     * jetpack_schedulesテーブルにはjetpack_order_idはない。
     * jetpack_ordersテーブルにjetpack_schedule_idがある。
     */
    public function jetpackOrder()
    {
        return $this->hasOne(JetpackOrder::class);
    }
}
