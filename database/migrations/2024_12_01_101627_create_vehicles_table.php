<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateVehiclesTable extends Migration
{
    public function up()
    {
        Schema::create('vehicles', function (Blueprint $table) {
            $table->id();
            $table->string('name')->nullable();
            $table->string('code')->nullable();
            $table->string('number');
            $table->integer('capacity')->nullable();
            $table->unsignedBigInteger('vehicle_type_id');
            $table->unsignedBigInteger('partner_id')->nullable();
            $table->unsignedBigInteger('worker_id')->nullable();
            $table->timestamps();
            $table->softDeletes(); // deleted_at カラム
        });
    }

    public function down()
    {
        Schema::dropIfExists('vehicles');
    }
}

