<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class JetpackOrder extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'jetpack_orders';

    protected $fillable = [
        'date_id',
        'vehicle_id',
        'jetpack_schedule_id',
        'jetpack_destination_id',
        'quantity',
        'status',
        'note',
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
     * jetpack_destinationsテーブルとリレーション
     * 予定の行き先を管理
     */
    public function jetpackDestination()
    {
        return $this->belongsTo(JetpackDestination::class);
    }


    /**
     * jetpack_schedulesテーブルとリレーション
     * 受注を含めた予定の大分類を管理
     * 一対一のリレーション
     * jetpack_schedulesテーブルにはjetpack_order_idはない。
     * jetpack_ordersテーブルにjetpack_schedule_idがある。
     */
    public function jetpackSchedule()
    {
        return $this->belongsTo(JetpackSchedule::class);
    }
}
