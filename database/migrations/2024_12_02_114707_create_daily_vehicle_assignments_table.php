<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('daily_vehicle_assignments', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('date_id');
            $table->unsignedBigInteger('work_type_id');
            $table->unsignedBigInteger('vehicle_id');
            $table->unsignedBigInteger('worker_id')->nullable(); // 作業員は任意;
            $table->string('sub_worker')->nullable(); // サブ作業員は任意
            $table->time('start_time')->nullable();// 始業時間は任意
            $table->integer('task_priority')->nullable();//業務の優先度は任意
            $table->integer('driver_task_order')->nullable();//同一ドライバーの作業順序は任意
            $table->text('notes')->nullable(); // 備考は任意
            $table->timestamps();
            $table->softDeletes(); // deleted_atカラムを作成
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('daily_vehicle_assignments');
    }
};
