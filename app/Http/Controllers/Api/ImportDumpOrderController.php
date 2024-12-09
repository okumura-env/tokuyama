<?php

namespace App\Http\Controllers\Api;

use Illuminate\Support\Facades\Storage;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use PhpOffice\PhpSpreadsheet\Cell\Coordinate;

use App\Models\Date;
use App\Models\Vehicle;
use App\Models\DumpOrder;
use App\Models\DumpOrderCategoryTitle;
use App\Models\DumpSchedule;
use App\Models\DailyVehicleAssignment;

class ImportDumpOrderController extends Controller
{
      // 条件分岐など
      public function import(Request $request)
      {
        $request->validate([
            'file' => 'required|file|mimes:xlsx',
        ]);
    
        // ファイルを保存
        $filePath = $request->file('file')->store('uploads');
    
        // ファイル内容の処理
        try {
            $file = \PhpOffice\PhpSpreadsheet\IOFactory::load(Storage::path($filePath));
            $sheet = $file->getActiveSheet();

            // 列範囲を定義
            $columnRanges = [
                ['start' => 'F', 'end' => 'K'],
                ['start' => 'M', 'end' => 'R'],
                ['start' => 'T', 'end' => 'Y'],
                ['start' => 'AA', 'end' => 'AF'],
                ['start' => 'AH', 'end' => 'AM'],
                ['start' => 'AO', 'end' => 'AT'],
            ];

            //行範囲の定義
            $rowRanges = [
                ['start' => 30, 'end' => 46, 'step' => 2],
                ['start' => 60, 'end' => 66, 'step' => 2],
            ];


            // 行範囲と列範囲を組み合わせて処理
            foreach ($rowRanges as $rowRange) {
                for ($rowStart = $rowRange['start']; $rowStart <= $rowRange['end']; $rowStart += $rowRange['step']) {
                    $rowEnd = $rowStart + 1;

                    foreach ($columnRanges as $range) {
                        $this->getData($sheet, $range['start'], $range['end'], $rowStart, $rowEnd);
                    }
                }
            }
           
            return response()->json(['message' => 'データが正常にインポートされました！']);
        } catch (\Exception $e) {
            return response()->json(['error' => 'データの処理中にエラーが発生しました: ' . $e->getMessage()], 500);
        }
      }

    public function getData($sheet,$startColumn,$endColumn,$rowStart,$rowEnd){
        for ($col = $startColumn; $col <= $endColumn; $col++) {
            $boilerNumber = $sheet->getCell("{$col}{$rowStart}")->getValue(); // 一行目のボイラー番号
            $categoryTitle = trim($sheet->getCell("{$col}{$rowEnd}")->getValue()); // 二行目のカテゴリタイトル
            
            // 空欄の場合をスキップ
            if (empty($categoryTitle)) {
                error_log("カテゴリタイトルが空欄です。セル: {$col}{$rowEnd} をスキップしました。");
                continue; // 処理をスキップ
            }
           
            // dump_order_category_titles で該当値を検索
            $categoryTitleId = DumpOrderCategoryTitle::where('title', $categoryTitle)->first()->id;
           
             // 該当するカテゴリがない場合をスキップ
            if (is_null($categoryTitleId)) {
                error_log("タイトル {$categoryTitle} が dump_order_categorie_titles に見つかりません。スキップします。");
                continue; // 処理をスキップ
            }
    
            // 列を表す文字列数値に変換し、1つ前の列番号を取得
            $previousColIndex = Coordinate::columnIndexFromString($startColumn)-1;
            // 列番号を再び文字列に変換
            $previousCol = Coordinate::stringFromColumnIndex($previousColIndex);

            // 日付とタスク優先度の列を取得(受注データの一つ前の列)
            $date_col = $previousCol;
            $task_priority_col = $previousCol;
            
            $taskPriority =  $sheet->getCell("{$task_priority_col}{$rowEnd}")->getValue();
            $dateRaw = $sheet->getCell("{$date_col}2")->getValue();

            // 取得した値がnullまたは空の場合、ログ出力して処理をスキップ
            if (empty($dateRaw)) {
                error_log("日付が取得できません。セル: {$date_col}2");
                continue; // 次のセルに進む
            }

            // 日付フォーマットの確認と変換
            try {
                // Excel内部日付形式の場合
                if (\PhpOffice\PhpSpreadsheet\Shared\Date::isDateTime($sheet->getCell("{$date_col}2"))) {
                    $date = \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($dateRaw)->format('Y-m-d');
                } else {
                    // 日本語形式のテキストを手動でパースする
                    $parsedDate = \DateTime::createFromFormat('n月j日', trim($dateRaw));
                    if ($parsedDate === false) {
                        // フォーマットが一致しない場合のログ
                        error_log("日付フォーマットが一致しません: {$dateRaw} (セル: {$date_col}2)");
                        continue; // スキップ
                    }
                    $date = $parsedDate->format('Y-m-d');
                }
            } catch (\Exception $e) {
                // 例外が発生した場合のログ
                error_log("日付のフォーマットに失敗しました: {$dateRaw} (エラー: {$e->getMessage()})");
                continue; // 日付が不正な場合はスキップ
            }

            // デバッグ用ログ
            error_log("取得した日付: {$date} (セル: {$date_col}2)");

            // date_idを取得
            $dateId = Date::query()->where('date', $date)->value('id');
            if (!$dateId) {
                return response()->json(['error' => "日付 {$date} が見つかりません"], 404);
            }

            // 各行の車両を処理
    
                $vehicleName = $sheet->getCell("B{$rowEnd}")->getValue(); // 車両名（仮定としてB列）
                $vehicleId = Vehicle::query()->where('name', $vehicleName)->value('id');
                
                if (!$vehicleId) {
                    return response()->json(['error' => "車両 {$vehicleName} が見つかりません"], 404);
                }
               
                // dump_ordersテーブルに保存
                $this->store($dateId, $vehicleId, $boilerNumber, $categoryTitleId, $categoryTitle, $taskPriority);
              
            
        }

    }
  
      // 保存処理
    public function store($dateId, $vehicleId, $boilerNumber, $categoryTitleId, $categoryTitle, $taskPriority)      
    {
        $assignment = DailyVehicleAssignment::where('date_id', $dateId)
        ->where('vehicle_id', $vehicleId)
        ->first();

        if ($assignment) {
            $dailyVehicleAssignmentId = $assignment->id;
        } else {     
            $assignment = DailyVehicleAssignment::create([
            'date_id' => $dateId,
            'vehicle_id' => $vehicleId,
            'work_type_id' => 1, // (ダンプ)固定値
            'worker_id' => null,
            'sub_worker' => null,
            'start_time' => null,
            'task_priority' => $taskPriority,
            'driver_task_order' => null,
            'notes' => null,
            'created_at' => now(),
            'updated_at' => now(),
            ]);
            $dailyVehicleAssignmentId = $assignment->id;
        }

        //同一日付、同一車両の中の全体のスケジュールの順番
        $sortOrder = DumpSchedule::where('date_id', $dateId)
        ->where('vehicle_id', $vehicleId)
        ->count() + 1;

        $dump_schedule = DumpSchedule::create([
        'date_id' => $dateId,
        'vehicle_id' => $vehicleId,
        'dump_order_category_id' => 1, //(HES)固定値
        'dump_order_category_title_id' => $categoryTitleId,
        'dump_order_category_title' => $categoryTitle,
        'schedule_type' => "orders", // (受注)固定値
        'sort_order' => $sortOrder, 
        'created_at' => now(),
        'updated_at' => now(),
        ]);

        DumpOrder::create([
        'date_id' => $dateId,
        'vehicle_id' => $vehicleId,
        'dump_schedule_id' => $dump_schedule->id,
        'daily_vehicle_assignment_id' => $dailyVehicleAssignmentId,
        'boiler_number' => $boilerNumber,
        'status' => 1, // (配車済み)固定値
        'is_preloaded' => 0, // (積込なし)固定値
        'vehicle_number' => null, // 固定値
        'notes' => null, // 固定値
        'created_at' => now(),
        'updated_at' => now(),
        ]);
    }
}
