<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('dump_orders', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('date_id');
            $table->unsignedBigInteger('vehicle_id');
            $table->unsignedBigInteger('schedule_id');
            $table->unsignedBigInteger('daily_vehicle_assignment_id')->nullable();
            $table->unsignedBigInteger('dump_order_category_id');// 例)HES,MCM,...
            $table->unsignedBigInteger('dump_order_category_title_id');// 例)リデ,MM,MO,CL,...
            $table->string('boiler_number')->nullable(); // ボイラー番号
            $table->boolean('status')->default(false); // ステータス(未配車 or 配車済)
            $table->boolean('is_preloaded')->default(false); // 事前の積込みあり or なし
            $table->string('vehicle_number')->nullable(); // 車両番号(富士運輸)
            $table->text('notes')->nullable(); // 備考
            $table->timestamps();
            $table->softDeletes(); // 論理削除
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('dump_orders');
    }
};
