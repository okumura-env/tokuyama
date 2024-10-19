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
        Schema::create('partners', function (Blueprint $table) {
            $table->id(); // 自動インクリメントのID
            $table->string('name', 255); // パートナー名
            $table->string('color', 100); // 色 (例: #FFFFFF)
            $table->softDeletes(); // ソフトデリート用のカラム (deleted_at)
            $table->timestamps(); // 作成日と更新日

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('partners');
    }
};
