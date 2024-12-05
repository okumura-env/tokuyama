<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\DumpOtherScheduleRequest;
use App\Http\Resources\DumpOtherScheduleResource;
use App\Models\DumpOtherSchedule;
use Illuminate\Http\Request;

class DumpOtherScheduleController extends Controller
{
    public function index()
    {
        return DumpOtherScheduleResource::collection(DumpOtherSchedule::all());
    }

    public function store(DumpOtherScheduleRequest $request)
    {
        $dumpOtherSchedule = DumpOtherSchedule::create($request->validated());
        return new DumpOtherScheduleResource($dumpOtherSchedule);
    }

    public function show(DumpOtherSchedule $dumpOtherSchedule)
    {
        return new DumpOtherScheduleResource($dumpOtherSchedule);
    }

    public function update(DumpOtherScheduleRequest $request, DumpOtherSchedule $dumpOtherSchedule)
    {
        $dumpOtherSchedule->update($request->validated());
        return new DumpOtherScheduleResource($dumpOtherSchedule);
    }

    public function destroy(DumpOtherSchedule $dumpOtherSchedule)
    {
        $dumpOtherSchedule->delete();
        return response()->noContent();
    }
}
