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
        Schema::create('jetpack_schedules', function (Blueprint $table) {
            $table->id();
            $table->foreignId('date_id')->constrained()->cascadeOnDelete();
            $table->foreignId('vehicle_id')->constrained()->cascadeOnDelete()->nullable();
            $table->foreignId('date_vehicle_id')->constrained('date_vehicle')->cascadeOnDelete()->nullable();//constrainedに正しいテーブル名を設定しないとdate_vehicle"s"テーブルとして認識されてしまう
            $table->integer('cell_number')->nullable(); // セル番号（任意）
            $table->integer('order_sequence')->nullable(); // オーダーの順序（任意）
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
        Schema::dropIfExists('jetpack_schedules');
    }
};
