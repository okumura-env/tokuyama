<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class OtherSchedule extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'other_schedules';

    protected $fillable = [
        'date_id',
        'vehicle_id',
        'dump_schedule_id',
        'notes',
    ];

    /**
     * datesテーブルとリレーション
     * その他の予定の日付を管理
     */
    public function date()
    {
        return $this->belongsTo(Date::class);
    }

    /**
     * vehiclesテーブルとリレーション
     * その他の予定ごとの車両を管理
     */
    public function vehicle()
    {
        return $this->belongsTo(Vehicle::class);
    }

    /**
     * dump_schedulesテーブルとリレーション
     * その他予定を含めた予定の大分類を管理
     */
    public function dumpSchedule()
    {
        return $this->hasOne(DumpSchedule::class);
    }

}
