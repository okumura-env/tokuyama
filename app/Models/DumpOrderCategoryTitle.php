<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DumpOrderCategoryTitle extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'dump_order_category_id',
    ];

    /**
     * dump_order_categoriesテーブルとリレーション
     * タスクを大まかに分けた大分類を管理
     */
    public function dumpOrderCategory()
    {
        return $this->belongsTo(DumpOrderCategory::class);
    }

    /**
     * dump_schedulesテーブルとリレーション
     * 受注ごとに行うタスクを管理
     */
    public function dumpSchedules()
    {
        return $this->hasMany(DumpSchedule::class);
    }

}
