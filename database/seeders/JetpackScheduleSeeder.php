<?php

namespace Database\Seeders;

use App\Models\JetpackSchedule;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class JetpackScheduleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        $schedules = [
            [
                'date_id' => 1,
                'vehicle_id' => 1,
                'date_vehicle_id' => 1,
                'cell_number' => null,
                'order_sequence' => null,
            ],
            [
                'date_id' => 2,
                'vehicle_id' => 1,
                'date_vehicle_id' => 2,
                'cell_number' => null,
                'order_sequence' => null,
            ],
            [
                'date_id' => 3,
                'vehicle_id' => 1,
                'date_vehicle_id' => 3,
                'cell_number' => null,
                'order_sequence' => null,
            ],
            [
                'date_id' => 4,
                'vehicle_id' => 1,
                'date_vehicle_id' => 4,
                'cell_number' => null,
                'order_sequence' => null,
            ],
            [
                'date_id' => 5,
                'vehicle_id' => 1,
                'date_vehicle_id' => 5,
                'cell_number' => null,
                'order_sequence' => null,
            ],
            [
                'date_id' => 6,
                'vehicle_id' => 1,
                'date_vehicle_id' => 6,
                'cell_number' => null,
                'order_sequence' => null,
            ],
            [
                'date_id' => 7,
                'vehicle_id' => 1,
                'date_vehicle_id' => 7,
                'cell_number' => null,
                'order_sequence' => null,
            ],
            [
                'date_id' => 8,
                'vehicle_id' => 1,
                'date_vehicle_id' => 8,
                'cell_number' => null,
                'order_sequence' => null,
            ],
            [
                'date_id' => 9,
                'vehicle_id' => 1,
                'date_vehicle_id' => 9,
                'cell_number' => null,
                'order_sequence' => null,
            ],
            [
                'date_id' => 10,
                'vehicle_id' => 1,
                'date_vehicle_id' => 10,
                'cell_number' => null,
                'order_sequence' => null,
            ],
            [
                'date_id' => 11,
                'vehicle_id' => 1,
                'date_vehicle_id' => 11,
                'cell_number' => null,
                'order_sequence' => null,
            ],
            [
                'date_id' => 12,
                'vehicle_id' => 1,
                'date_vehicle_id' => 12,
                'cell_number' => null,
                'order_sequence' => null,
            ]
            ];
 
        foreach($schedules as $schedule) {
            JetpackSchedule::create($schedule);
        }
    }
}
