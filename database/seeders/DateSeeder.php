<?php

namespace Database\Seeders;

use App\Models\Date;
use Illuminate\Database\Seeder;

class DateSeeder extends Seeder
{
    public function run(): void
    {
        $dates = [
            '2024-11-25',
            '2024-11-26',
            '2024-11-27',
            '2024-11-28',
            '2024-11-29',
            '2024-11-30',
            '2024-12-02',
            '2024-12-03',
            '2024-12-04',
            '2024-12-05',
            '2024-12-06',
            '2024-12-07',
        ];

        foreach ($dates as $date) {
            Date::create([
                'date' => $date,
                'jetpack_note' => null, // jetpack_noteをnullに設定
            ]);
        }
    }
}
