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
            $table->foreignId('date_id')->constrained()->cascadeOnDelete();
            $table->foreignId('vehicle_id')->constrained()->cascadeOnDelete()->nullable();
            $table->foreignId('dump_schedule_id')->constrained()->cascadeOnDelete();
            $table->string('boiler_number')->nullable(); // ボイラー番号
            $table->boolean('status')->default(false); // ステータス(未配車 or 配車済)
            $table->boolean('is_preloaded')->default(false); // 事前の積込みあり or なし
            $table->string('vehicle_number')->nullable(); // 車両番号(富士運輸)
            $table->text('note')->nullable(); // 備考
            $table->timestamps();
            $table->softDeletes(); // 論理削除
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('dump_orders');
    }
};
