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
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Log;
use Spatie\LaravelIgnition\Recorders\DumpRecorder\Dump;

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
        dd($request->all());
    }
}
