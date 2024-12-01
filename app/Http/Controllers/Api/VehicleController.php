<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\VehicleRequest;
use App\Http\Resources\VehicleResource;
use App\Models\Vehicle;
use Illuminate\Http\Request;

class VehicleController extends Controller
{
    // 一覧取得
    public function index()
    {
        $vehicles = Vehicle::all();
        return VehicleResource::collection($vehicles);
    }

    // 特定データ取得
    public function show($id)
    {
        $vehicle = Vehicle::findOrFail($id);
        return new VehicleResource($vehicle);
    }

    // 新規作成
    public function store(VehicleRequest $request)
    {
        $vehicle = Vehicle::create($request->validated());
        return new VehicleResource($vehicle);
    }

    // 更新
    public function update(VehicleRequest $request, $id)
    {
        $vehicle = Vehicle::findOrFail($id);
        $vehicle->update($request->validated());
        return new VehicleResource($vehicle);
    }

    // 削除
    public function destroy($id)
    {
        $vehicle = Vehicle::findOrFail($id);
        $vehicle->delete();
        return response()->json(['message' => 'Deleted successfully'], 200);
    }
}
