<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use App\Models\Date;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call([
            PartnerSeeder::class,
            DateSeeder::class,
            WorkerSeeder::class,
            VehicleTypeSeeder::class,
            VehicleSeeder::class,
            DumpOrderCategorySeeder::class,
            DumpOrderCategoryTitleSeeder::class,
            WorkTypeSeeder::class,
            DateVehicleSeeder::class,
            McmTaskTypeSeeder::class,
            RuleSeeder::class,
            McmCoalUsageScheduleSeeder::class,
            JetpackDestinationRouteSeeder::class,
        ]);
    }
}
