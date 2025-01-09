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
use App\Models\McmCoalUsageSchedule;
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
        $dateData = $request->dateData;
        $vehicleCount = $request->vehicleCount;
        $fujiVehicleIds = Vehicle::where('name','like', '%富士%')->pluck('id')->toArray();
        $mcmQuantity = null;
        
        if($vehicleCount !== "未選択"){
            foreach($dateData as $dateDatum){
                for ($i = 1; $i <= $vehicleCount; $i++) {
                    //曜日ごとの初めの登録の際は初期化&予定数量を入れ直す
                    if($mcmQuantity === null){
                        $mcmQuantity = $dateDatum['mcmQuantity'];
                    }
                    
                    // 一つの車両が同じ日につき2回石炭を運ぶ
                    for ($j = 1; $j <= 2; $j++) {
                        $dateVehicle = DateVehicle::where('date_id',$dateDatum['id'])
                            ->where('vehicle_id',$fujiVehicleIds[$i-1])
                            ->first();
                        $sort = DumpSchedule::where('date_id',$dateDatum['id'])
                            ->where('vehicle_id',$fujiVehicleIds[$i-1])
                            ->count() + 1;
                        $dumpSchedule = DumpSchedule::create([
                            'date_id' => $dateDatum['id'],
                            'vehicle_id' => $fujiVehicleIds[$i-1],
                            'date_vehicle_id' => $dateVehicle->id,
                            'dump_order_category_id' => 2,
                            'dump_order_category_title_id' => 7,
                            'dump_order_category_title' => DumpOrderCategoryTitle::find(7)->title,
                            'schedule_type' => "orders", // (受注)固定値
                            'sort' => $sort, 
                        ]);
                        $dumpOrder = $dumpSchedule->dumpOrder()->create([
                            'date_id' => $dateDatum['id'],
                            'vehicle_id' => $fujiVehicleIds[$i-1],
                            'boiler_number' => null,
                            'status' => true, 
                            'is_preloaded' => false, 
                            'vehicle_number' => null, 
                            'note' => null, 
                        ]);

                          //mcmQuantityから20引く(20t車で一回運んだときの数量)
                          $mcmQuantity -= 20;
                    }
                }
                $McmCoalUsageSchedule = McmCoalUsageSchedule::where('date_id',$dateDatum['id'])
                ->first();
                $McmCoalUsageSchedule->update([
                    'temporary_amount' => $mcmQuantity
                ]); 
                $mcmQuantity = null;
            }
        }
    }

    public function mcmRuledScheduleStore(Request $request)
    { 
       $dateData = $request->dateData;
      
       $selectedRuleData = Rule::where("name", $request->selectedRule)->get();
    
       //1.MCM石炭のスケジュールデータを取得
       //2.曜日ごとにグループ化
       //3.priorityを基準に昇順に並び替え
       //$mcmCoalRuleData =[
        //     "月曜日" => [
        //         ["id" => 306, "name" => "ルール6","day_of_week" => "月曜日", "vehicle_id" => 9,"priority" => 1,...],
        //         ["id" => 310, "name" => "ルール6", "day_of_week" => "月曜日","vehicle_id" => 13,"priority" => 2,...],
        //       ...
        //     ],
        //     "火曜日" => [
        //         ["id" => 320, "name" => "ルール6", "day_of_week" => "火曜日","vehicle_id" => 13,"priority" => 1,...],
        //         ["id" => 311, "name" => "ルール6", "day_of_week" => "火曜日","vehicle_id" => 2,"priority" => 2,...],
        //       ...
        //     ],
        //     ...
        // ];
        $mcmCoalRuleData = $selectedRuleData
        ->where('mcm_task_type_id', 1)
        ->groupBy('day_of_week')
        ->map(fn($items) => collect($items)->sortBy('priority')->values())
        ->toArray();

        $daysOfWeek = ["月曜日","火曜日","水曜日","木曜日","金曜日","土曜日"];

        foreach($daysOfWeek as $dayOfWeek){
            //特定の曜日のみのデータ
            //$mcmCoalRuleDataByDay = [
                //         ["id" => 306, "name" => "ルール6","day_of_week" => "月曜日", "vehicle_id" => 9,"priority" => 1,...],
                //         ["id" => 310, "name" => "ルール6", "day_of_week" => "月曜日","vehicle_id" => 13,"priority" => 2,...],
                //       ...
                //     ],
            $mcmCoalRuleDataByDay = $mcmCoalRuleData[$dayOfWeek];

              //MCM石炭のスケジュール登録
                $mcmQuantity = null; // MCMオーダー数量の変数初期化
                foreach($mcmCoalRuleDataByDay  as $ruleDatumByDay){
                    //$dateData = [
                    //     ["id" => 1,"date" => "2024-12-02", "day_of_week" => "月曜日", "mcmQuantity" => 320,...],
                    //     ["id" => 2,"date" => "2024-12-03", "day_of_week" => "火曜日", "mcmQuantity" => 280,...],
                    //     ...
                    // ];
                    foreach($dateData as $date){
                        if($dayOfWeek == $date['day_of_week']){
                            Log::info("一致");
                            // 初回ループ時に $mcmQuantity を初期化
                            if ($mcmQuantity === null) {
                                $mcmQuantity = McmCoalUsageSchedule::where('date_id',$date['id'])
                                    ->first()->temporary_amount;
                            }
                            $dateId = $date['id'];

                            //同じ日付の同じ車両が基本2回石炭を運ぶ
                            for ($j = 1; $j <= 2; $j++) {
                                $dateVehicle = DateVehicle::where('date_id',$dateId)
                                    ->where('vehicle_id',$ruleDatumByDay["vehicle_id"])
                                    ->first();
                                $sort = DumpSchedule::where('date_id',$dateId)
                                    ->where('vehicle_id',$ruleDatumByDay["vehicle_id"])
                                    ->count() + 1;
                                $dumpSchedule = DumpSchedule::create([
                                    'date_id' => $dateId,
                                    'vehicle_id' => $ruleDatumByDay["vehicle_id"],
                                    'date_vehicle_id' => $dateVehicle->id,
                                    'dump_order_category_id' => 2,
                                    'dump_order_category_title_id' => 7,
                                    'dump_order_category_title' => DumpOrderCategoryTitle::find(7)->title,
                                    'schedule_type' => "orders", // (受注)固定値
                                    'sort' => $sort, 
                                ]);
                                $dumpOrder = $dumpSchedule->dumpOrder()->create([
                                    'date_id' => $dateId,
                                    'vehicle_id' => $ruleDatumByDay["vehicle_id"],
                                    'boiler_number' => null,
                                    'status' => true, 
                                    'is_preloaded' => false, 
                                    'vehicle_number' => null, 
                                    'note' => null, 
                                ]);
                                
                                //mcmQuantityから20引く(20t車で一回運んだときの数量)
                                $mcmQuantity -= 20;
                                //予定数量が0以下になったら必要分割り当てたということなのでループを抜ける
                                if($mcmQuantity <= 0){
                                    //初期化
                                    $mcmQuantity = null;
                                    break;
                                }
                            }
                        }else{
                        Log::info("不一致");
                        }
                    }
                    if($mcmQuantity === null){
                        break;
                    }
                }
        }
     

       
    }

    public function tenScheduleStore(Request $request){    
        $dateData = $request->dateData;
        $tenRuleDataByDay = Rule::where("name", $request->selectedRule)
                            ->where('mcm_task_type_id', 2)
                            ->get();

        foreach($dateData as $date){
            foreach($tenRuleDataByDay as $ruleDatumByDay){
                if($date['day_of_week']==$ruleDatumByDay["day_of_week"]){
                    // dd($date['day_of_week'],$ruleDatumByDay["day_of_week"]);
                $dateVehicle = DateVehicle::where('date_id',$date["id"])
                ->where('vehicle_id',$ruleDatumByDay["vehicle_id"])
                ->first();
                $sort = DumpSchedule::where('date_id',$date["id"])
                    ->where('vehicle_id',$ruleDatumByDay["vehicle_id"])
                    ->count() + 1;
                $dumpSchedule = DumpSchedule::create([
                    'date_id' => $date["id"],
                    'vehicle_id' => $ruleDatumByDay["vehicle_id"],
                    'date_vehicle_id' => $dateVehicle->id,
                    'dump_order_category_id' => 4,
                    'dump_order_category_title_id' => 13,
                    'dump_order_category_title' => DumpOrderCategoryTitle::find(13)->title,
                    'schedule_type' => "orders", // (受注)固定値
                    'sort' => $sort, 
                ]);
                $dumpOrder = $dumpSchedule->dumpOrder()->create([
                    'date_id' => $date["id"],
                    'vehicle_id' => $ruleDatumByDay["vehicle_id"],
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
