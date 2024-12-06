<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\DumpOrderRequest;
use App\Http\Resources\DumpOrderResource;
use App\Models\DumpOrder;

class DumpOrderController extends Controller
{
    public function index()
    {
        return DumpOrderResource::collection(DumpOrder::with('dumpSchedule','dailyVehicleAssignment')->get());
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
}
