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
}
