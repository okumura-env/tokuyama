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
        Schema::create('mcm_coal_usage_schedules', function (Blueprint $table) {
            $table->id();
            $table->foreignId('date_id')->constrained()->cascadeOnDelete(); // 日付ID
            $table->integer('planned_amount')->nullable(); // 計画使用量
            $table->integer('temporary_amount')->nullable()->comment('富士の配車後に一時的に保存する数量');
            $table->integer('usage_amount')->nullable(); // 実際使用量
            $table->text('note')->nullable(); // 備考
            $table->softDeletes(); // 論理削除用のカラム
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('mcm_coal_usage_schedules');
    }
};
