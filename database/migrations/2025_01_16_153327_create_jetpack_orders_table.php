<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('jetpack_orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('date_id'); // 日付ID
            $table->foreignId('vehicle_id')->nullable(); // 車両ID（任意）
            $table->foreignId('jetpack_schedule_id')->constrained()->cascadeOnDelete();
            $table->foreignId('jetpack_destination_route_id'); // 行き先ID
            $table->integer('quantity')->nullable(); // 数量（任意）
            $table->string('status'); // ステータス
            $table->text('note')->nullable(); // 備考（任意）
            $table->timestamps();
            $table->softDeletes(); // deleted_at を追加
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('jetpack_orders');
    }
};
