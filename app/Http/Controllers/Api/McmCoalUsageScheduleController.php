<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\McmCoalUsageScheduleRequest;
use App\Http\Resources\McmCoalUsageScheduleResource;
use App\Models\McmCoalUsageSchedule;

class McmCoalUsageScheduleController extends Controller
{
    public function index()
    {
        return McmCoalUsageScheduleResource::collection(McmCoalUsageSchedule::all());
    }

    public function store(McmCoalUsageScheduleRequest $request)
    {
        $schedule = McmCoalUsageSchedule::create($request->validated());
        return new McmCoalUsageScheduleResource($schedule);
    }

    public function show(McmCoalUsageSchedule $schedule)
    {
        return new McmCoalUsageScheduleResource($schedule);
    }

    public function update(McmCoalUsageScheduleRequest $request, McmCoalUsageSchedule $schedule)
    {
        $schedule->update($request->validated());
        return new McmCoalUsageScheduleResource($schedule);
    }

    public function destroy(McmCoalUsageSchedule $schedule)
    {
        $schedule->delete();
        return response()->noContent();
    }
}
