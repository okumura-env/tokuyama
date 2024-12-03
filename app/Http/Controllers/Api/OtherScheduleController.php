<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\OtherScheduleRequest;
use App\Http\Resources\OtherScheduleResource;
use App\Models\OtherSchedule;
use Illuminate\Http\Request;

class OtherScheduleController extends Controller
{
    public function index()
    {
        return OtherScheduleResource::collection(OtherSchedule::all());
    }

    public function store(OtherScheduleRequest $request)
    {
        $otherSchedule = OtherSchedule::create($request->validated());
        return new OtherScheduleResource($otherSchedule);
    }

    public function show(OtherSchedule $otherSchedule)
    {
        return new OtherScheduleResource($otherSchedule);
    }

    public function update(OtherScheduleRequest $request, OtherSchedule $otherSchedule)
    {
        $otherSchedule->update($request->validated());
        return new OtherScheduleResource($otherSchedule);
    }

    public function destroy(OtherSchedule $otherSchedule)
    {
        $otherSchedule->delete();
        return response()->noContent();
    }
}
