<?php

// database/factories/WorkerFactory.php
namespace Database\Factories;

use App\Models\Worker;
use Illuminate\Database\Eloquent\Factories\Factory;

class WorkerFactory extends Factory
{
    protected $model = Worker::class;

    public function definition()
    {
        // Fakerを日本語ロケールで生成
        $faker = \Faker\Factory::create('ja_JP');

        return [
            'name' => $faker->name, // 日本語の氏名
            'note' => $faker->sentence, // 日本語のランダムな文
        ];
    }
}

