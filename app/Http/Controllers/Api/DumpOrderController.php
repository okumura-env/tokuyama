<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\DumpOrderRequest;
use App\Http\Resources\DumpOrderResource;
use App\Models\DumpOrder;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\DumpOrderImport;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Log;

class DumpOrderController extends Controller
{
    public function index()
    {
        return DumpOrderResource::collection(DumpOrder::with('dumpSchedule')->get());
    }

    public function store(DumpOrderRequest $request)
    {
        $dumpOrder = DumpOrder::create($request->validated());
        return new DumpOrderResource($dumpOrder);
    }

    public function show(DumpOrder $dumpOrder)
    {
        return new DumpOrderResource($dumpOrder);
    }

    public function update(DumpOrderRequest $request, DumpOrder $dumpOrder)
    {
        $dumpOrder->update($request->validated());
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
}
