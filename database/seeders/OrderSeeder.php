<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

use App\Models\Order;
use App\Models\Vehicle;

class OrderSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $orderDate = [
            '2024-10-07',
            '2024-10-08',
            '2024-10-09',
            '2024-10-10',
            '2024-10-11',
            '2024-10-12',
        ];

        $vehicle_ids = Vehicle::all()->pluck('id');
        foreach ($orderDate as $date) {
            foreach($vehicle_ids as $vehicle_id){
                Order::create([
                    'order_date' => $date,
                    'vehicle_id' => $vehicle_id
                ]);
            }
        }
    }
}
