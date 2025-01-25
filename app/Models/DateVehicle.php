<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;

class DateVehicle extends Pivot
{

    /**
     * work_typesテーブルとリレーション
     * 日毎の車両情報を管理する際の「ジェットパック」「ダンプ」「WP・PKS業務」「その他」かを管理
     */
    public function workType()
    {
        return $this->belongsTo(WorkType::class);
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
     * dump_schedulesテーブルとリレーション
     * 同一日付・車両の組み合わせでのダンプスケジュールを管理
     */
    public function dumpSchedules()
    {
        return $this->hasMany(DumpSchedule::class);
    }

     /**
     * jetpack_schedulesテーブルとリレーション
     * 同一日付・車両の組み合わせでのジェットパックスケジュールを管理
     * 
     */
    public function jetpackSchedules()
    {
        return $this->hasMany(JetpackSchedule::class);
    }
}
