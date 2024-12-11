<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('dump_schedules', function (Blueprint $table) {
            $table->id();
            $table->foreignId('date_id')->constrained()->cascadeOnDelete();
            $table->foreignId('vehicle_id')->constrained()->cascadeOnDelete()->nullable();
            $table->foreignId('date_vehicle_id')->constrained('date_vehicle')->cascadeOnDelete()->nullable();//constrainedに正しいテーブル名を設定しないとdate_vehicle"s"テーブルとして認識されてしまう
            $table->foreignId('dump_order_category_id')->constrained()->cascadeOnDelete();// 例)HES,MCM,...
            $table->foreignId('dump_order_category_title_id')->constrained()->cascadeOnDelete()->nullable();// 例)リデ,MM,MO,CL,...
            $table->string('dump_order_category_title')->nullable();
            $table->string('schedule_type');
            $table->integer('sort');
            $table->timestamps();
            $table->softDeletes(); // deleted_at
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('dump_schedules');
    }
};
