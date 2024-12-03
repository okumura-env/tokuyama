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
            $table->unsignedBigInteger('vehicle_id');
            $table->unsignedBigInteger('dump_order_category_id');
            $table->unsignedBigInteger('dump_order_category_title_id');
            $table->string('dump_order_category_title');
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
