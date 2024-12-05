<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('dump_other_schedules', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('date_id');
            $table->unsignedBigInteger('vehicle_id');
            $table->unsignedBigInteger('dump_schedule_id');
            $table->text('note')->nullable(); // 備考は任意
            $table->timestamps();
            $table->softDeletes(); // 論理削除
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('dump_other_schedules');
    }
};
