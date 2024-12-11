<?php

namespace Database\Seeders;

use App\Models\Vehicle;
use App\Models\Date;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DateVehicleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $vehicles = Vehicle::all();
        $dates = Date::all();

        foreach ($vehicles as $vehicle) {
            foreach ($dates as $date) {
                $vehicle->dates()->attach($date->id, [
                    'task_priority' => null,
                    'work_type_id' => 1,//仮にダンプ
                ]);
            }
        }
    }
}
