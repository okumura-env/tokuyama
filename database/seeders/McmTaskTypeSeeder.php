<?php

namespace Database\Seeders;

use App\Models\McmTaskType;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class McmTaskTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $mcmTaskTypes = [
            ['name' => 'MCM'],
            ['name' => '転'],
            ['name' => 'その他'],
        ];

        foreach ($mcmTaskTypes as $mcmTaskType) {
            McmTaskType::create($mcmTaskType);
        }
    }
}
