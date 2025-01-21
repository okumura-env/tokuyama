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
        Schema::create('jetpack_destinations', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // 行先名
            $table->timestamps(); // 作成日と更新日
            $table->softDeletes(); // 論理削除用のカラム
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('jetpack_destinations');
    }
};
