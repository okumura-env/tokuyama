<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class DumpSchedule extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'dump_schedules';

    protected $fillable = [
        'date_id',
        'vehicle_id',
        'dump_order_category_id',
        'dump_order_category_title_id',
        'dump_order_category_title',
        'schedule_type',
        'sort_order',
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
     * dump_ordersテーブルとリレーション
     * 受注を含めた予定の大分類を管理
     */
    public function dumpOrder()
    {
        return $this->hasOne(DumpOrder::class);
    }

    /**
     * dump_other_schedulesテーブルとリレーション
     * その他予定を含めた予定の大分類を管理
     */
    public function OtherSchedule()
    {
        return $this->hasOne(OtherSchedule::class);
    }

}
