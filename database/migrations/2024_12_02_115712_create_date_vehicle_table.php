<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('date_vehicle', function (Blueprint $table) {
            $table->id();
            $table->foreignId('date_id')->constrained()->cascadeOnDelete();
            $table->foreignId('vehicle_id')->constrained()->cascadeOnDelete()->nullable();
            $table->foreignId('work_type_id')->constrained()->cascadeOnDelete();
            $table->foreignId('worker_id')->constrained()->cascadeOnDelete();
            $table->string('sub_worker')->nullable();
            $table->time('start_time')->nullable();
            $table->integer('task_priority')->default(0);
            $table->integer('driver_task_order')->default(0);
            $table->text('note')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('date_vehicle');
    }
};
