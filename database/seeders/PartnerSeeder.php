<?php

namespace Database\Seeders;

use App\Models\Partner;
use Illuminate\Database\Seeder;

class PartnerSeeder extends Seeder
{
    public function run()
    {
        $partners = [
            [
                'name' => 'トクヤマ',
                'color' => '#075ACC',
            ],
            [
                'name' => '奥村',
                'color' => '#C4F8C2',
            ],
            [
                'name' => '港湾',
                'color' => '#79AA63',
            ],
            [
                'name' => '興洋',
                'color' => '#EDF514',
            ],
            [
                'name' => '上組',
                'color' => '#84BBEA',
            ],
            [
                'name' => '湯野',
                'color' => '#5E9EBF',
            ],
            [
                'name' => 'キチナン',
                'color' => '#E2E3C8',
            ],
            [
                'name' => '富士',
                'color' => '#F5CA68',
            ],
        ];

        foreach ($partners as $partner) {
            Partner::create($partner);
        }
    }
}
