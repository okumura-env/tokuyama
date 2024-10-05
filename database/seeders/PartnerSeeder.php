<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

use App\Models\Partner;

class PartnerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $partnerNames = [
            '奥村',
            '港湾',
            '興洋',
            '富士',
            '上組'
        ];

        foreach ($partnerNames as $partnerName) {
            Partner::create([
                'name' => $partnerName,
            ]);
        }
    }
}
