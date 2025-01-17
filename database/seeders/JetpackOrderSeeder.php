<?php

namespace Database\Seeders;

use App\Models\JetpackOrder;
use App\Models\JetpackSchedule;
use Illuminate\Database\Seeder;

class JetpackOrderSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $maxPerCombination = 3; // 同じ日付・車両の組み合わせの最大件数
        $totalData = 260;       // 作成するデータの総数
        $combinations = [];     // 日付・車両の組み合わせを追跡

        for ($createdCount = 0; $createdCount < $totalData; $createdCount++) {
            $date_id = rand(1, 12);
            $vehicle_id = null; // vehicle_idはnull固定

            // この組み合わせのデータ件数を確認
            $combinationKey = $date_id . '-' . ($vehicle_id ?? 'null');
            $currentCount = $combinations[$combinationKey] ?? 0;

            if ($currentCount < $maxPerCombination) {
                // JetpackScheduleを取得
                $schedule = JetpackSchedule::where('date_id', $date_id)
                ->first();

                // データを作成
                JetpackOrder::create([
                    'date_id' => $date_id,
                    'vehicle_id' => $vehicle_id,
                    'jetpack_schedule_id' => $schedule ? $schedule->id : null,
                    'jetpack_destination_route_id' => rand(1, 15),
                    'quantity' => null,
                    'status' => 0,
                    'note' => null,
                ]);

                // カウントを更新
                $combinations[$combinationKey] = $currentCount + 1;
            } else {
                // この場合、ループをやり直すためカウントを減らす
                $createdCount--;
            }
        }
    }
}
