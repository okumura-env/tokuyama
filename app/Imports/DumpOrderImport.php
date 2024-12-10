<?php

namespace App\Imports;

use App\Models\Vehicle;
use App\Models\DumpOrder;
use App\Models\DumpSchedule;
use App\Models\DumpOrderCategoryTitle;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithStartRow;
use Illuminate\Support\Facades\Log;


class DumpOrderImport implements ToCollection, WithStartRow
{
    protected $dateIds;

    // フロントで画面に表示されている日付をプロパティに格納
    public function __construct($dateIds)
    {
        $this->dateIds = $dateIds;
    }

    /**
     * 開始行を指定するメソッド
     * ここで返した行数から実データの読み込みを開始する
     */
    public function startRow(): int
    {
        return 30; // 例: 30行目(奥村(1318))からデータとして扱う
    }

    public function collection(Collection $rows)
    {
        // 曜日ごとのカラムインデックスを定義
        // 0: 月曜, 1: 火曜, 2: 水曜, 3: 木曜, 4: 金曜, 5: 土曜
        $dayColumnMappings = [
            // $dateIndex => [taskPriorityColumn, orderTitleAndBoilerStartColumn, orderTitleAndBoilerEndColumn]
            0 => [ 'taskPriority' => 4,  'orderTitleAndBoilerStartColumn' => 5,  'orderTitleAndBoilerEndColumn' => 10 ],
            1 => [ 'taskPriority' => 11, 'orderTitleAndBoilerStartColumn' => 12, 'orderTitleAndBoilerEndColumn' => 17 ],
            2 => [ 'taskPriority' => 18, 'orderTitleAndBoilerStartColumn' => 19, 'orderTitleAndBoilerEndColumn' => 24 ],
            3 => [ 'taskPriority' => 25, 'orderTitleAndBoilerStartColumn' => 26, 'orderTitleAndBoilerEndColumn' => 31 ],
            4 => [ 'taskPriority' => 32, 'orderTitleAndBoilerStartColumn' => 33, 'orderTitleAndBoilerEndColumn' => 38 ],
            5 => [ 'taskPriority' => 39, 'orderTitleAndBoilerStartColumn' => 40, 'orderTitleAndBoilerEndColumn' => 45 ],
        ];

        foreach ($this->dateIds as $dateIndex => $dateId) {
            // 定義がない日付インデックスはスキップ
            if (!isset($dayColumnMappings[$dateIndex])) {
                continue;
            }

            $dailyColumnConfig = $dayColumnMappings[$dateIndex];
            $boilerNumbers = null;
            $vehicle = null;
            $taskPriority = null;
            $orderTitles = null;

            foreach ($rows as $rowIndex => $row) {
                // 偶数行でボイラナンバーを取得
                if ($rowIndex % 2 === 0) {
                    $boilerNumbers = $row->slice($dailyColumnConfig['orderTitleAndBoilerStartColumn'], ($dailyColumnConfig['orderTitleAndBoilerEndColumn'] - $dailyColumnConfig['orderTitleAndBoilerStartColumn'] + 1));
                    Log::info($boilerNumbers);
                } else {
                    // 奇数行でその他情報を取得
                    $vehicle = Vehicle::where("name", $row[1])->first();
                    $taskPriority = $row[$dailyColumnConfig['taskPriority']];
                    $orderTitles = $row->slice($dailyColumnConfig['orderTitleAndBoilerStartColumn'], ($dailyColumnConfig['orderTitleAndBoilerEndColumn'] - $dailyColumnConfig['orderTitleAndBoilerStartColumn'] + 1));
    
                }

                // 必要な変数が揃ったら保存処理実行
                if ($boilerNumbers !== null && $vehicle !== null && $orderTitles !== null) {
                    $this->createDumpOrder($dateId, $vehicle, $boilerNumbers, $taskPriority, $orderTitles);

                    // 次回に備えてリセット
                    $boilerNumbers = null;
                    $vehicle = null;
                    $taskPriority = null;
                    $orderTitles = null;
                }
            }
        }
    }

    private function createDumpOrder($dateId, $vehicle, $boilerNumbers, $taskPriority, $orderTitles)
    {
        $dateVehicle = $vehicle->dates()->wherePivot('date_id',$dateId)->first();

        if ($dateVehicle) {
            $dateVehicleId = $dateVehicle->id;
        } else {     
            $vehicle->dates()->syncWithoutDetaching([$dateId => [
                'work_type_id' => 1, // (ダンプ)固定値
                'worker_id' => null,
                'sub_worker' => null,
                'start_time' => null,
                'task_priority' => $taskPriority,
                'driver_task_order' => null,
                'note' => null,
                ]
            ]);

         
            $dateVehicleId = $vehicle->dates()->wherePivot('date_id', $dateId)->first()->pivot->id;
        }      

        foreach ($boilerNumbers as $index => $boilerNumber) {
            //同一日付、同一車両の中の全体のスケジュールの順番
            // $index: 5, 6, 7, 8, 9, 10(月曜日の場合)
            // $index: 12, 13, 14, 15, 16, 17(火曜日の場合)...となる
            $sort = ($index + 3) % 7; 

            $orderTitle = $orderTitles[$index];
            if($orderTitle !== null && $boilerNumber !== null){
                $orderTitleId = DumpOrderCategoryTitle::where('title', $orderTitle)->first()->id;

                $dump_schedule = DumpSchedule::create([
                    'date_id' => $dateId,
                    'vehicle_id' => $vehicle->id,
                    'date_vehicle_id' => $dateVehicleId,
                    'dump_order_category_id' => 1, //(HES)固定値
                    'dump_order_category_title_id' => $orderTitleId,
                    'dump_order_category_title' => $orderTitle,
                    'schedule_type' => "orders", // (受注)固定値
                    'sort' => $sort, 
                ]);
        
                $dumpScheduleId = $dump_schedule->id;    
                DumpOrder::create([
                    'date_id' => $dateId,
                    'vehicle_id' => $vehicle->id,
                    'dump_schedule_id' => $dumpScheduleId,
                    'date_vehicle_id' => null,//ひとまず,
                    'boiler_number' => $boilerNumber,
                    'status' => 1, // (配車済み)固定値
                    'is_preloaded' => 0, // (積込なし)固定値
                    'vehicle_number' => null, // 固定値
                    'notes' => null, // 固定値
                ]);
            }
        }
    }

}