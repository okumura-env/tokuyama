<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class DumpOrderCategory extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = ['name'];

    /**
     * dump_order_category_titlesテーブルとリレーション
     * タスクを大まかに分けた大分類を管理
     */
    public function dumpOrderCategoryTitles()
    {
        return $this->hasMany(DumpOrderCategoryTitle::class);
    }

    /**
     * dump_schedulesテーブルとリレーション
     * 受注ごとに行うタスクの大分類を管理
     */
    public function dumpSchedules()
    {
        return $this->hasMany(DumpSchedule::class);
    }

}
