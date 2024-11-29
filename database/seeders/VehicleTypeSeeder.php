<?php

namespace Database\Seeders;

use App\Models\VehicleType;
use Illuminate\Database\Seeder;

class VehicleTypeSeeder extends Seeder
{
    public function run()
    {
        $vehicleTypes = [
            ['type_name' => 'ダンプトレーラー'],
            ['type_name' => 'ダンプ(単車)'],
            ['type_name' => 'ジェットパック'],
            ['type_name' => '6t車'],
            ['type_name' => 'その他'],
        ];

        foreach ($vehicleTypes as $vehicleType) {
            VehicleType::create($vehicleType);
        }
    }
}

