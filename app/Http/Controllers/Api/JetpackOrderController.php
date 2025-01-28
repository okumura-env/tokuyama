<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Http\Requests\JetpackOrderRequest;
use App\Http\Resources\JetpackOrderResource;
use App\Models\Date;
use App\Models\JetpackOrder;
use App\Models\DateVehicle;
use App\Models\JetpackSchedule;

class JetpackOrderController extends Controller
{
    public function index()
    {
        return JetpackOrderResource::collection(JetpackOrder::all());
    }

    public function store(JetpackOrderRequest $request)
    {
        $order = JetpackOrder::create($request->validated());
        return new JetpackOrderResource($order);
    }

    public function show(JetpackOrder $order)
    {
        return new JetpackOrderResource($order);
    }

    public function update(JetpackOrderRequest $request, JetpackOrder $order)
    {
        $order->update($request->validated());
        return new JetpackOrderResource($order);
    }

    public function destroy(JetpackOrder $order)
    {
        $order->delete();
        return response()->noContent();
    }

    public function fetchJetpackOrdersByDate($date)
    {
        $jetpackOrders = JetpackOrder::where('date_id', $date)->get(); 
        return JetpackOrderResource::collection($jetpackOrders);
    }

    public function registerJetpackOrdersByDestination(Request $request)
    {
        $jetpackDestinationId = $request->jetpack_destination_id;
        $schedules = $request->schedules;
    
        // 変更点: $schedules のループ処理が全ての要素で動作するように確認
        foreach ($schedules as $schedule) {
            // 変更点: 'orderCounts' のチェックを強化
            if (isset($schedule['orderCounts']) && $schedule['orderCounts'] > 0) {
                $orderCounts = (int)$schedule['orderCounts'];
    
                // 日付ごとのフォームに入力した回数分オーダーを登録する
                for ($i = 0; $i < $orderCounts; $i++) {
                    // 変更点: DateVehicle の取得が正しく行えるようチェック追加
                    
                        $jetpackSchedule = JetpackSchedule::create([
                            'date_id' => $schedule['dateId'],
                        ]);
    
                        $jetpackSchedule->jetpackOrder()->create([
                            'date_id' => $schedule['dateId'],
                            'jetpack_destination_id' => $jetpackDestinationId,
                            'status' => false,
                        ]);
                  
                }
            } else {
                // orderCounts が存在しないまたは 0 以下の場合のエラーログ
                Log::warning("Invalid or missing 'orderCounts' for schedule: " . json_encode($schedule));
            }
        }
    }

    public function dispatchJetpackOrders(Request $request)
    {
      
        //車両ごとの予定を格納
        $ordersByVehicles = $request->all();

        foreach($ordersByVehicles as $ordersByVehicle) {

            // 搬出先、回数の列が3行ずつあるので、3回ループ
            for($i = 1; $i <= 3; $i++){
                $jetpackOrderId   = 'jetpack_order' . $i . '_id';
                $countsKey    = 'counts'        . $i;
                $cellNumber = $i;
                if(!empty($ordersByVehicle[$jetpackOrderId])) {
                //後で使用するため一度変数に格納
                //findに続けて->update()とすると$jetpackOrderにtrueが格納されてしまうので注意
                $jetpackOrder = JetpackOrder::find($ordersByVehicle[$jetpackOrderId]);
                $jetpackOrder->update(
                        [
                            'vehicle_id' => $ordersByVehicle['vehicle_id'],
                            'count' => $ordersByVehicle[$countsKey],
                            'status' => true,
                            'note' => $ordersByVehicle['note']
                        ]
                    );

                    //後で使用するため一度変数に格納
                    $dateVehicle = DateVehicle::where("date_id",$ordersByVehicle['date_id'])
                    ->where("vehicle_id",$ordersByVehicle['vehicle_id'])
                    ->where("work_type_id",2)//work_type_id=2はジェットパックの意
                    ->first();
                    $dateVehicle->update(
                        [
                            'worker_id' => $ordersByVehicle['worker_id'],
                            'sub_worker' => $ordersByVehicle['sub_worker'],
                            'start_time' => $ordersByVehicle['start_time'],
                        ]
                    );

        
                    JetpackSchedule::find($jetpackOrder->jetpack_schedule_id)->update(
                        [
                            'vehicle_id' => $ordersByVehicle['vehicle_id'],
                            'date_vehicle_id' => $dateVehicle->id,
                            'cell_number' => $cellNumber,
                        ]
                    );
                }
            }

        }
    }
}
