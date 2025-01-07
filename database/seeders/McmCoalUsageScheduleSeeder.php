<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\McmCoalUsageSchedule;
use Illuminate\Database\Seeder;

class McmCoalUsageScheduleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $amounts = [
            ['date_id' => '1',
             'planned_amount' => '320',
            ],
            ['date_id' => '2',
             'planned_amount' => '300',
            ],
            ['date_id' => '3',
             'planned_amount' => '280',
            ],
            ['date_id' => '4',
             'planned_amount' => '260',
            ],
            ['date_id' => '5',
             'planned_amount' => '300',
            ],
            ['date_id' => '6',
             'planned_amount' => '320',
            ],
            ['date_id' => '7',
             'planned_amount' => '300',
            ],
            ['date_id' => '8',
             'planned_amount' => '320',
            ],
            ['date_id' => '9',
             'planned_amount' => '280',
            ],
            ['date_id' => '10',
             'planned_amount' => '340',
            ],
            ['date_id' => '11',
             'planned_amount' => '320',
            ],
            ['date_id' => '12',
             'planned_amount' => '300',
            ],
        ];

        foreach ($amounts as $amount) {
                McmCoalUsageSchedule::create([
                    'date_id' => $amount['date_id'],
                    'planned_amount' => $amount['planned_amount'],
                    'temporary_amount' => null,
                    'usage_amount' => null,
                    'note' => null,
                ]);
        }
    }
}
