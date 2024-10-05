<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

use App\Models\Vehicle;
use App\Models\Partner;

class VehicleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $vehicleNames = [
            '奥村(1318)',
            '奥村(390)',
            '奥村(1353)',
            '港湾(11号)',
            '港湾(10号)',
            '港湾(8498)',
            '港湾(8504)',
            '上組(8967)',
        ];
        foreach ($vehicleNames as $vehicleName) {
            $partnerName = mb_substr($vehicleName,0,2);
            Vehicle::create([
                'name' => $vehicleName,
                'partner_id' => Partner::where('name',$partnerName)->first()->id
            ]);
        }
 
        

    }
}
