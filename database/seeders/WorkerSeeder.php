<?php

// database/seeders/WorkerSeeder.php
namespace Database\Seeders;

use App\Models\Worker;
use Illuminate\Database\Seeder;

class WorkerSeeder extends Seeder
{
    public function run()
    {
        Worker::factory(40)->create(); // 40件のレコードを生成
    }
}
