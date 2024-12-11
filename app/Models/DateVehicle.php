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
}
