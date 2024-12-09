<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class WorkType extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
    ];

    /**
     * date_vehicleテーブルとリレーション
     * 日毎の車両情報を管理する際の「ジェットパック」「ダンプ」「WP・PKS業務」「その他」かを管理
     */
    public function dateVehicle()
    {
        return $this->hasMany(DateVehicle::class);
    }

}
