<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\VehicleTypeRequest;
use App\Http\Resources\VehicleTypeResource;
use App\Models\VehicleType;
use Illuminate\Http\Response;

class VehicleTypeController extends Controller
{
    // 全データを取得
    public function index()
    {
        return VehicleTypeResource::collection(VehicleType::all());
    }

    // 新規作成
    public function store(VehicleTypeRequest $request)
    {
        $vehicleType = VehicleType::create($request->validated());
        return new VehicleTypeResource($vehicleType);
    }

    // 詳細取得
    public function show(VehicleType $vehicleType)
    {
        return new VehicleTypeResource($vehicleType);
    }

    // 更新
    public function update(VehicleTypeRequest $request, VehicleType $vehicleType)
    {
        $vehicleType->update($request->validated());
        return new VehicleTypeResource($vehicleType);
    }

    // 削除
    public function destroy(VehicleType $vehicleType)
    {
        $vehicleType->delete();
        return response(null, Response::HTTP_NO_CONTENT);
    }
}
