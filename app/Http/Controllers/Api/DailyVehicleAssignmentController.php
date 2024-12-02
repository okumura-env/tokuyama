<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\DailyVehicleAssignmentRequest;
use App\Http\Resources\DailyVehicleAssignmentResource;
use App\Models\DailyVehicleAssignment;
use Illuminate\Http\Request;

class DailyVehicleAssignmentController extends Controller
{
    public function index()
    {
        return DailyVehicleAssignmentResource::collection(
            DailyVehicleAssignment::all()
        );
    }

    public function store(DailyVehicleAssignmentRequest $request)
    {
        $assignment = DailyVehicleAssignment::create($request->validated());
        return new DailyVehicleAssignmentResource($assignment);
    }

    public function show(DailyVehicleAssignment $dailyVehicleAssignment)
    {
        return new DailyVehicleAssignmentResource($dailyVehicleAssignment);
    }

    public function update(DailyVehicleAssignmentRequest $request, DailyVehicleAssignment $dailyVehicleAssignment)
    {
        $dailyVehicleAssignment->update($request->validated());
        return new DailyVehicleAssignmentResource($dailyVehicleAssignment);
    }

    public function destroy(DailyVehicleAssignment $dailyVehicleAssignment)
    {
        $dailyVehicleAssignment->delete();
        return response()->noContent();
    }
}
