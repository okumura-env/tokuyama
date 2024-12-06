<?php

namespace Database\Seeders;

use App\Models\VehicleType;
use Illuminate\Database\Seeder;

class VehicleTypeSeeder extends Seeder
{
    public function run()
    {
        $vehicleTypes = [
            ['name' => 'ダンプトレーラー'],
            ['name' => 'ダンプ(単車)'],
            ['name' => 'ジェットパック'],
            ['name' => '6t車'],
            ['name' => 'その他'],
        ];

        foreach ($vehicleTypes as $vehicleType) {
            VehicleType::create($vehicleType);
        }
    }
}

