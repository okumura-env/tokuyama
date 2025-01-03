<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\DumpOrderRequest;
use App\Http\Resources\DumpOrderResource;
use App\Models\DumpOrder;
use App\Models\DumpSchedule;
use App\Models\DateVehicle;
use App\Models\DumpOrderCategoryTitle;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\DumpOrderImport;
use App\Models\McmTaskType;
use App\Models\Vehicle;
use App\Models\Rule;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Log;
use Spatie\LaravelIgnition\Recorders\DumpRecorder\Dump;
use Carbon\Carbon;

class DumpOrderController extends Controller
{
    public function index()
    {
        return DumpOrderResource::collection(DumpOrder::with('dumpSchedule.dateVehicle')->get());
    }

    public function store(DumpOrderRequest $request)
    {
        // リクエストデータを取得
        //$data = [
            // 'date_id' => '1',
            // 'vehicle_id' => 2,
            // 'dump_schedule_id' => '1',
            // 'dump_order_category_id' => 2,
            // 'dump_order_category_title_id' => 1,
            // 'boiler_number' => '1',
            // 'status' => true,
            // 'is_preloaded' => true,
            // 'note' => 'p',
            //]
        $data = $request->validated();
        Log::info($data);
        $dateVehicle = DateVehicle::where('date_id',$data['date_id'])
            ->where('vehicle_id',$data['vehicle_id'])
            ->first();
        $sort = DumpSchedule::where('date_id',$data['date_id'])
            ->where('vehicle_id',$data['vehicle_id'])
            ->count() + 1;
        $dumpSchedule = DumpSchedule::create([
            'date_id' => $data['date_id'],
            'vehicle_id' => $data['vehicle_id'],
            'date_vehicle_id' => $dateVehicle->id,
            'dump_order_category_id' => $data['dump_order_category_id'],
            'dump_order_category_title_id' => $data['dump_order_category_title_id'],
            'dump_order_category_title' => DumpOrderCategoryTitle::find($data['dump_order_category_title_id'])->title,
            'schedule_type' => "orders", // (受注)固定値
            'sort' => $sort, 
        ]);
        $dumpOrder = $dumpSchedule->dumpOrder()->create([
            'date_id' => $data['date_id'],
            'vehicle_id' => $data['vehicle_id'],
            'boiler_number' => $data['boiler_number'],
            'status' => $data['status'], 
            'is_preloaded' => $data['is_preloaded'], 
            'vehicle_number' => null, // 固定値
            'note' => $data['note'], // 固定値
        ]);
        return new DumpOrderResource($dumpOrder);
    }

    public function show(DumpOrder $dumpOrder)
    {
        return new DumpOrderResource($dumpOrder);
    }

    public function update(DumpOrderRequest $request, DumpOrder $dumpOrder)
    {
        // dd($request->validated(), $dumpOrder->id);
        $data = $request->validated();
        //dump_schedulesテーブルとdump_ordersテーブルは対応しているデータのidが同じ
        $dumpSchedule = DumpSchedule::find($dumpOrder->id); 
        $dumpSchedule->update([
            'dump_order_category_id' => $data['dump_order_category_id'],
            'dump_order_category_title_id' => $data['dump_order_category_title_id'],
            'dump_order_category_title' => DumpOrderCategoryTitle::find($data['dump_order_category_title_id'])->title,
        ]);

        $dumpOrder->update([
            'boiler_number' => $data['boiler_number'],
            'status' => $data['status'],
            'is_preloaded' => $data['is_preloaded'],
            'note' => $data['note'],
        ]);
         
        return new DumpOrderResource($dumpOrder);
    }

    public function destroy(DumpOrder $dumpOrder)
    {
        $dumpOrder->delete();
        return response()->noContent();
    }

    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|file|mimes:xlsx',
        ]);

        //フロント側から画面に表示されている日付Idsを取得
        $dateIds = collect(json_decode($request->dates, true))->pluck('id')->toArray();
        $import = new DumpOrderImport($dateIds);

        try {
            // Excelインポートの実行
            Excel::import( $import , $request->file);

            return response()->json(['message' => 'データが正常にインポートされました！']);
        } catch (\Exception $e) {
            return response()->json(['error' => 'データの処理中にエラーが発生しました: ' . $e->getMessage()], 500);
        }
    }

    public function fujiScheduleStore(Request $request)
    { 
        $dateIds = collect($request->dateData)->pluck("id");
        $vehicleCount = $request->vehicleCount;
        $fujiVehicleIds = Vehicle::where('name','like', '%富士%')->pluck('id')->toArray();
        
        if($vehicleCount !== "未選択"){
            for ($i = 1; $i <= $vehicleCount; $i++) {
                foreach($dateIds as $dateId){
                    // 一つの車両が同じ日につき2回石炭を運ぶ
                    for ($j = 1; $j <= 2; $j++) {
                        $dateVehicle = DateVehicle::where('date_id',$dateId)
                            ->where('vehicle_id',$fujiVehicleIds[$i-1])
                            ->first();
                        $sort = DumpSchedule::where('date_id',$dateId)
                            ->where('vehicle_id',$fujiVehicleIds[$i-1])
                            ->count() + 1;
                        $dumpSchedule = DumpSchedule::create([
                            'date_id' => $dateId,
                            'vehicle_id' => $fujiVehicleIds[$i-1],
                            'date_vehicle_id' => $dateVehicle->id,
                            'dump_order_category_id' => 2,
                            'dump_order_category_title_id' => 7,
                            'dump_order_category_title' => DumpOrderCategoryTitle::find(7)->title,
                            'schedule_type' => "orders", // (受注)固定値
                            'sort' => $sort, 
                        ]);
                        $dumpOrder = $dumpSchedule->dumpOrder()->create([
                            'date_id' => $dateId,
                            'vehicle_id' => $fujiVehicleIds[$i-1],
                            'boiler_number' => null,
                            'status' => true, 
                            'is_preloaded' => false, 
                            'vehicle_number' => null, 
                            'note' => null, 
                        ]);
                    }
                }
            }
        }
    }

    public function mcmRuledScheduleStore(Request $request)
    { 
       $dates = $request->dateData;
      
       $selectedRuleData = Rule::where("name", $request->selectedRule)->get();

       // タスクタイプIDを一度に取得
       $taskTypeIds = McmTaskType::whereIn('name', ['MCM', '転', 'その他'])
           ->pluck('id', 'name');
       
       // ルールデータをタスクタイプによってフィルタリング
       $mcmCoalRuleData = $selectedRuleData->where('mcm_task_type_id', $taskTypeIds['MCM']);
       $tenRuleData = $selectedRuleData->where('mcm_task_type_id', $taskTypeIds['転']);
       $otherRuleData = $selectedRuleData->where('mcm_task_type_id', $taskTypeIds['その他']);

       //MCM石炭のスケジュール登録
       foreach($mcmCoalRuleData  as $ruleData){
        //  dd($ruleData->day_of_week);
         foreach($dates as $date){
            // dd($date['day_of_week'],$date['id']);
            if($ruleData->day_of_week == $date['day_of_week']){
                Log::info("一致");
                $dateId = $date['id'];
                Log::info($dateId);

                for ($j = 1; $j <= 2; $j++) {
                    $dateVehicle = DateVehicle::where('date_id',$dateId)
                        ->where('vehicle_id',$ruleData->vehicle_id)
                        ->first();
                    $sort = DumpSchedule::where('date_id',$dateId)
                        ->where('vehicle_id',$ruleData->vehicle_id)
                        ->count() + 1;
                    $dumpSchedule = DumpSchedule::create([
                        'date_id' => $dateId,
                        'vehicle_id' => $ruleData->vehicle_id,
                        'date_vehicle_id' => $dateVehicle->id,
                        'dump_order_category_id' => 2,
                        'dump_order_category_title_id' => 7,
                        'dump_order_category_title' => DumpOrderCategoryTitle::find(7)->title,
                        'schedule_type' => "orders", // (受注)固定値
                        'sort' => $sort, 
                    ]);
                    $dumpOrder = $dumpSchedule->dumpOrder()->create([
                        'date_id' => $dateId,
                        'vehicle_id' => $ruleData->vehicle_id,
                        'boiler_number' => null,
                        'status' => true, 
                        'is_preloaded' => false, 
                        'vehicle_number' => null, 
                        'note' => null, 
                    ]);
                }
            }else{
            Log::info("不一致");
            }
    
            
         }
       }

       
    }
}
