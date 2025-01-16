<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\JetpackScheduleRequest;
use App\Http\Resources\JetpackScheduleResource;
use App\Models\JetpackSchedule;

class JetpackScheduleController extends Controller
{
    public function index()
    {
        return JetpackScheduleResource::collection(JetpackSchedule::all());
    }

    public function store(JetpackScheduleRequest $request)
    {
        $schedule = JetpackSchedule::create($request->validated());
        return new JetpackScheduleResource($schedule);
    }

    public function show(JetpackSchedule $schedule)
    {
        return new JetpackScheduleResource($schedule);
    }

    public function update(JetpackScheduleRequest $request, JetpackSchedule $schedule)
    {
        $schedule->update($request->validated());
        return new JetpackScheduleResource($schedule);
    }

    public function destroy(JetpackSchedule $schedule)
    {
        $schedule->delete();
        return response()->noContent();
    }
}
