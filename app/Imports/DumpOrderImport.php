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

    /**
     *  (例)[ 7, 8, 9, 10, 11, 12,] 
     * @param array $dateIds 渡された日付idの配列
     */
    public function __construct($dateIds)
    {
        $this->dateIds = $dateIds;
    }

    /**
     * 開始行を指定するメソッド
     * ここで返した行数から実データの読み込みを開始する
     * @return int
     */
    public function startRow(): int
    {
        return 30; // 例: 30行目(奥村(1318))からデータとして扱う
    }

    /**
     *  Excelファイルのデータを処理
     * @param Collection $rows
     */
    public function collection(Collection $rows)
    {
        // 日付ごとに処理
        foreach ($this->dateIds as $dateIndex => $dateId) {
            // 各曜日ごとのセル開始位置(パターン)
            // taskPriorityは基準 + 4 + (曜日インデックス * 7)
            // boilerNumbers, orderTitlesは基準 + 5 + (曜日インデックス * 7) から6つ分
            $priorityIndex = 4 + ($dateIndex * 7);
            $boilerStartIndex = 5 + ($dateIndex * 7);

            // 一時的に保持する変数
            $boilerNumbers = null;
            $vehicle = null;
            $taskPriority = null;
            $orderTitles = null;

            // 行ごとに処理
            foreach ($rows as $rowIndex => $row) {
                if ($rowIndex % 2 === 0) {
                    // 偶数行：boilerNumbersのみ取得
                    // (例)$boilerNumbers：[ 1, 6, 1, NULL, NULL, NULL]
                    $boilerNumbers = collect($row)->slice($boilerStartIndex, 6)->values()->all();
                } else {
                    // 奇数行：vehicleId, taskPriority, orderTitlesを取得
                    // (例)$taskPriority：四
                    // (例)$orderTitles：[ リデ, MM, MM, NULL, NULL, NULL]
                    $vehicle = Vehicle::where("name", $row[1])->first();
                    $taskPriority = $row[$priorityIndex];
                    $orderTitles = collect($row)->slice($boilerStartIndex, 6)->values()->all();
                }

                // 必要な変数が揃ったら保存処理実行
                // $taskPriorityは必須ではないので条件に含めない
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
    

    /**
     * ダンプスケジュールを作成する
     * @param int $dateId
     * @param Vehicle $vehicle
     * @param array $boilerNumbers
     * @param int $taskPriority
     * @param array $orderTitles
     * 
     * @return void
     * @throws \Exception
     */
    private function createDumpOrder($dateId, $vehicle, $boilerNumbers, $taskPriority, $orderTitles)
    {
        // 該当日付、該当車両のスケジュールが存在するか確認する
        $dateVehicle = $vehicle->dates()->wherePivot('date_id',$dateId)->first();

        // 該当日付、該当車両のスケジュールが存在する場合、そのidを取得
        // 存在しない場合、新規作成
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

        // (例)$boilerNumbers：[ 1, 6, 1, NULL, NULL, NULL]
        Log::info($boilerNumbers);
        foreach ($boilerNumbers as $index => $boilerNumber) {
            //同一日付、同一車両の全てのスケジュールの順番
            // $index: 0, 1, 2, 3, 4, 5
            $sort = $index + 1; 

            // (例)$orderTitles：[ リデ, MM, MM, NULL, NULL, NULL]
            $orderTitle = $orderTitles[$index];
            // オーダーの表示名(title)とボイラー番号は必須項目
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
                    'date_vehicle_id' => $dateVehicleId,
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