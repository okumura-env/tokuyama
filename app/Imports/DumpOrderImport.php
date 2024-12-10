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
        foreach ($this->dateIds as $dateIndex => $dateId) {
            // 各曜日ごとのセル開始位置(パターン)
            // taskPriorityは基準 + 4 + (曜日インデックス * 7)
            // boilerNumbers, orderTitlesは基準 + 5 + (曜日インデックス * 7) から6つ分
            $priorityIndex = 4 + ($dateIndex * 7);
            $boilerStartIndex = 5 + ($dateIndex * 7);

            $boilerNumbers = null;
            $vehicle = null;
            $taskPriority = null;
            $orderTitles = null;

            foreach ($rows as $rowIndex => $row) {
                if ($rowIndex % 2 === 0) {
                    // 偶数行：boilerNumbersのみ取得
                    $boilerNumbers = collect($row)->slice($boilerStartIndex, 6)->values()->all();

                } else {
                    // 奇数行：vehicleId, taskPriority, orderTitlesを取得
                    $vehicle = Vehicle::where("name", $row[1])->first();
                    $taskPriority = $row[$priorityIndex];
                    $orderTitles = collect($row)->slice($boilerStartIndex, 6)->values()->all();
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
            $sort = $index + 1; 
            Log::debug("sort:".$sort);

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