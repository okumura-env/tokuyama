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
        Schema::create('jetpack_destination_routes', function (Blueprint $table) {
            $table->id(); // 主キー
            $table->string('name'); // ルート名
            $table->timestamps(); // 作成日時と更新日時
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
        Schema::dropIfExists('jetpack_destination_routes');
    }
};
