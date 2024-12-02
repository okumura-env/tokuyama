<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('work_types', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // 業務の種類の名前
            $table->timestamps();
            $table->softDeletes(); // 論理削除のためのdeleted_at
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('work_types');
    }
};
