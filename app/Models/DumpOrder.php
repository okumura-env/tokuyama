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
        'daily_vehicle_assignment_id',
        'boiler_number',
        'status',
        'is_preloaded',
        'vehicle_number',
        'notes',
    ];

    /**
     * daily_vehicle_assignmentsテーブルとリレーション
     * 日毎で統一の車両情報を管理
     */
    public function dailyVehicleAssignment()
    {
        return $this->belongsTo(DailyVehicleAssignment::class);
    }

    /**
     * dump_order_categoriesテーブルとリレーション
     * 受注ごとに行うタスクの大分類を管理
     */
    public function dumpOrderCategory()
    {
        return $this->belongsTo(DumpOrderCategory::class);
    }

    /**
     * dump_order_category_titlesテーブルとリレーション
     * 受注ごとに行うタスクを管理
     */
    public function dumpOrderCategoryTitle()
    {
        return $this->belongsTo(DumpOrderCategoryTitle::class);
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
