<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\McmTaskTypeRequest;
use App\Http\Resources\McmTaskTypeResource;
use App\Models\McmTaskType;

class McmTaskTypeController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function index()
    {
        return McmTaskTypeResource::collection(McmTaskType::all());
    }

    /**
     * Store a newly created resource in storage.
     * @param McmTaskTypeRequest $request
     * @return McmTaskTypeResource
     * 
     */
    public function store(McmTaskTypeRequest $request)
    {
        $mcmTaskType = McmTaskType::create($request->validated());
        return new McmTaskTypeResource($mcmTaskType);
    }

    /**
     * Display the specified resource.
     * @param McmTaskType $mcmTaskType
     * @return McmTaskTypeResource
     * 
     */
    public function show(McmTaskType $mcmTaskType)
    {
        return new McmTaskTypeResource($mcmTaskType);
    }

    /**
     * Update the specified resource in storage.
     * @param McmTaskTypeRequest $request
     * @param McmTaskType $mcmTaskType
     * @return McmTaskTypeResource
     * 
     */
    public function update(McmTaskTypeRequest $request, McmTaskType $mcmTaskType)
    {
        $mcmTaskType->update($request->validated());
        return new McmTaskTypeResource($mcmTaskType);
    }

    /**
     * Remove the specified resource from storage.
     * @param McmTaskType $mcmTaskType
     * @return \Illuminate\Http\Response
     * 
     */
    public function destroy(McmTaskType $mcmTaskType)
    {
        $mcmTaskType->delete();
        return response()->noContent();
    }
}
