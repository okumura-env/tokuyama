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
        Schema::create('rules', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // ルール名
            $table->string('day_of_week'); // 曜日
            $table->foreignId('vehicle_id')->constrained()->cascadeOnDelete();
            $table->integer('priority'); // 優先度
            $table->foreignId('mcm_task_type_id')->constrained()->cascadeOnDelete();
            $table->timestamps();
            $table->softDeletes(); // deleted_at カラム
        });
    }
    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('rules');
    }
};
