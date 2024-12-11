<?php

namespace App\Imports;

use App\Models\Vehicle;
use App\Models\DumpOrder;
use App\Models\DumpSchedule;
use App\Models\DumpOrderCategoryTitle;
use App\Models\DateVehicle;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
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
            // 例）月曜日の場合、taskPriorityは基準 + 4 + (0 * 7) = 4（行目）
            // 例）火曜日の場合、taskPriorityは基準 + 4 + (1 * 7) = 11（行目）
            // boilerNumbers, orderTitlesは基準 + 5 + (曜日インデックス * 7) から6つ分
            // 例）月曜日の場合、boilerNumbers, orderTitlesは基準 + 5 + (0 * 7) = 5 (行目)から6つ分
            // 例）火曜日の場合、boilerNumbers, orderTitlesは基準 + 5 + (1 * 7) = 12 (行目)から6つ分
            $priorityIndex = 4 + ($dateIndex * 7);
            $boilerStartIndex = 5 + ($dateIndex * 7);

            // 1行ずつ処理
            // 偶数行と奇数行で処理を分ける
            // 偶数行：boilerNumbersのみ取得
            // 奇数行：vehicleId, taskPriority, orderTitlesを取得
            // 1つの受注につき偶数行と奇数行はセット。必ず偶数行から取得処理が始まる。例)1つの受注情報がExcelの30行目と31行目に渡って記載されている。
            // 必要な変数が揃ったら保存処理実行
            // 次のループに備えてリセット
            foreach ($rows as $rowIndex => $row) {
                if ($rowIndex % 2 === 0) {
                    // 偶数行：boilerNumbersのみ取得
                    // (例)$boilerNumbers：[ 1, 6, 1, NULL, NULL, NULL]
                    $boilerNumbers = collect($row)->slice($boilerStartIndex, 6)->values()->all();
                    Log::info("Boiler Numbers: ", $boilerNumbers);
                } else {
                    // 奇数行：vehicleId, taskPriority, orderTitlesを取得
                    // (例)$taskPriority：四
                    // (例)$orderTitles：[ リデ, MM, MM, NULL, NULL, NULL]
                    $vehicle = Vehicle::where("name", $row[1])->first();
                    $taskPriority = $row[$priorityIndex];
                    $orderTitles = collect($row)->slice($boilerStartIndex, 6)->values()->all();

                    // 必要な変数が揃ったら保存処理実行
                    $this->createDumpOrder($dateId, $vehicle, $boilerNumbers ?? [], $taskPriority, $orderTitles);
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
        $dateVehicle = DateVehicle::where('date_id',$dateId)
        ->where('vehicle_id',$vehicle->id)
        ->first();
        $dateVehicle->update([
            'task_priority' => $taskPriority
        ]); 

        // (例)$boilerNumbers：[ 1, 6, 1, NULL, NULL, NULL]
        Log::info($boilerNumbers);
        foreach ($boilerNumbers as $index => $boilerNumber) {
            //同一日付、同一車両の全てのスケジュールの順番
            // $index: 0, 1, 2, 3, 4, 5
            // なぜ+1しているかは、スケジュールの順番は1から始まるため
            $sort = $index + 1; 

            // (例)$orderTitles：[ リデ, MM, MM, NULL, NULL, NULL]
            $orderTitle = $orderTitles[$index];

            // 注意：$orderTitle, $boilerNumber が NULL の場合はスキップ
            // オーダーの表示名(title)は必須項目のためエラーになる
            //注意：boilerNumberは実はnullableなので以下の条件に含める必要はないが、記載しないとエラーになるので追加
            //デバック時に確認
            if ($orderTitle === null || $boilerNumber === null) {
                continue;
            }

            //orderTotle取得時にまとめて取得したくなるがtitleがnullの可能性があるので上記の条件文を通った後に取得
            $orderTitleId = DumpOrderCategoryTitle::where('title', $orderTitle)->first()->id;

            $dumpSchedule = DumpSchedule::create([
                'date_id' => $dateId,
                'vehicle_id' => $vehicle->id,
                'date_vehicle_id' => $dateVehicle->id,
                'dump_order_category_id' => 1, //(HES)固定値
                'dump_order_category_title_id' => $orderTitleId,
                'dump_order_category_title' => $orderTitle,
                'schedule_type' => "orders", // (受注)固定値
                'sort' => $sort, 
            ]);
      
            $dumpSchedule->dumpOrder()->create([
                'date_id' => $dateId,
                'vehicle_id' => $vehicle->id,
                'date_vehicle_id' => $dateVehicle->id,
                'boiler_number' => $boilerNumber,
                'status' => 1, // (配車済み)固定値
                'is_preloaded' => 0, // (積込なし)固定値
                'vehicle_number' => null, // 固定値
                'notes' => null, // 固定値
            ]);
            
        }
    }

}