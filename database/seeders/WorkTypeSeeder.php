<?php

namespace Database\Seeders;

use App\Models\WorkType;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class WorkTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $workTypes = [
            ['name' => 'ダンプ'],
            ['name' => 'ジェットパック'],
            ['name' => 'WP・PKS/その他'],
        ];

        foreach ($workTypes as $workType) {
            WorkType::create($workType);
        }
    }
}
