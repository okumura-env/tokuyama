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
            $table->unsignedBigInteger('date_id');
            $table->unsignedBigInteger('vehicle_id')->nullable();
            $table->unsignedBigInteger('dump_order_category_id');// 例)HES,MCM,...
            $table->unsignedBigInteger('dump_order_category_title_id')->nullable();// 例)リデ,MM,MO,CL,...
            $table->string('dump_order_category_title')->nullable();
            $table->string('schedule_type');
            $table->integer('sort_order');
            $table->timestamps();
            $table->softDeletes(); // deleted_at
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('dump_schedules');
    }
};
