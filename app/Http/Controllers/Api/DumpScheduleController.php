<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\DumpScheduleRequest;
use App\Http\Resources\DumpScheduleResource;
use App\Models\DumpSchedule;
use Illuminate\Http\Request;

class DumpScheduleController extends Controller
{
    public function index()
    {
        return DumpScheduleResource::collection(DumpSchedule::with('dumpOrder','dateVehicle')->get());
    }

    public function store(DumpScheduleRequest $request)
    {
        $schedule = DumpSchedule::create($request->validated());
        return new DumpScheduleResource($schedule);
    }

    public function show(DumpSchedule $dumpSchedule)
    {
        return new DumpScheduleResource($dumpSchedule->load('dumpOrder','dateVehicle','date'));
    }

    public function update(DumpScheduleRequest $request, DumpSchedule $dumpSchedule)
    {
        $dumpSchedule->update($request->validated());
        return new DumpScheduleResource($dumpSchedule);
    }

    public function destroy(DumpSchedule $dumpSchedule)
    {
        $dumpSchedule->delete();
        return response()->noContent();
    }

    // 例：Laravelコントローラ内
    public function swap(Request $request)
    {
        $draggedId = $request->input('dragged.id'); // nullの場合あり
        $draggedNewSort = $request->input('dragged.newSort'); 
        $droppedId = $request->input('dropped.id'); // nullの場合あり
        $droppedNewSort = $request->input('dropped.newSort'); 

        // // 1. ドラッグ元レコードが存在する場合
        if ($draggedId) {
            DumpSchedule::find($draggedId)->update([
                'sort' => $draggedNewSort
            ]);
        } else {
            // ドラッグ元が空の場合、空のセルに関しては何も処理を行わない
        }

        // // 2. ドロップ先レコードが存在する場合
        if ($droppedId) {
            DumpSchedule::where('id', $droppedId)->update([
                'sort' => $droppedNewSort
            ]);
        } else {
            // ドラッグ先が空の場合、空のセルに関しては何も処理を行わない
        }

        return response()->json(['message' => 'OK'], 200);
    }

}
