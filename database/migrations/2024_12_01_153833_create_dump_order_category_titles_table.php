<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('dump_order_category_titles', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->foreignId('dump_order_category_id')->constrained()->cascadeOnDelete();
            $table->timestamps();
            $table->softDeletes(); // deleted_at カラムを追加
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('dump_order_category_titles');
    }
};
