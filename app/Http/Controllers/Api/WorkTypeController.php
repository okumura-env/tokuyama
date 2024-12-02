<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\WorkTypeRequest;
use App\Http\Resources\WorkTypeResource;
use App\Models\WorkType;
use Illuminate\Http\Request;

class WorkTypeController extends Controller
{
    // 一覧取得
    public function index()
    {
        return WorkTypeResource::collection(
            WorkType::all()
        );
    }

    // データ保存
    public function store(WorkTypeRequest $request)
    {
        $workType = WorkType::create($request->validated());
        return new WorkTypeResource($workType);
    }

    // データ詳細取得
    public function show(WorkType $workType)
    {
        return new WorkTypeResource($workType);
    }

    // データ更新
    public function update(WorkTypeRequest $request, WorkType $workType)
    {
        $workType->update($request->validated());
        return new WorkTypeResource($workType);
    }

    // データ削除
    public function destroy(WorkType $workType)
    {
        $workType->delete();
        return response()->noContent();
    }
}
