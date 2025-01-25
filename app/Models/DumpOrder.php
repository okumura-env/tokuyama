<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class DumpOrder extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'dump_orders';

    protected $fillable = [
        'date_id',
        'vehicle_id',
        'dump_schedule_id',
        'boiler_number',
        'status',
        'is_preloaded',
        'vehicle_number',
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
     * dump_schedulesテーブルとリレーション
     * 受注を含めた予定の大分類を管理
     * 一対一のリレーション
     * dump_ordersテーブルにdump_schedule_idがある。
     * dump_schedulesテーブルにはdump_order_idはない。
     */
    public function dumpSchedule()
    {
        return $this->belongsTo(DumpSchedule::class);
    }

}
