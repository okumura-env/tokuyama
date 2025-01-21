<?php

namespace Database\Seeders;

use App\Models\JetpackDestination;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class JetpackDestinationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $destinations = [
            ['name' => '出光バイオJT'],
            ['name' => '出光バイオ疋田積'],
            ['name' => '出光バイオ苅田'],
            ['name' => '石化ニチハ'],
            ['name' => '石化トクヤマ'],
            ['name' => '三菱ケミカル'],
            ['name' => 'HESトクヤマ'],
            ['name' => 'HES鹿野'],
            ['name' => 'HESニチハ'],
            ['name' => 'MCM'],
            ['name' => 'ケイミュー'],
            ['name' => 'ナイカイ'],
            ['name' => 'スカラベ'],
            ['name' => 'リライフ'],
            ['name' => 'EP鹿野'],
            ['name' => 'その他'],
        ];

        foreach ($destinations as $destination) {
            JetpackDestination::create($destination);
        }
    }
}
