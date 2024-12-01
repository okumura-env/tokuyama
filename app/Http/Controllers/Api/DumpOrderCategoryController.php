<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\DumpOrderCategoryRequest;
use App\Http\Resources\DumpOrderCategoryResource;
use App\Models\DumpOrderCategory;
use Illuminate\Http\Request;

class DumpOrderCategoryController extends Controller
{
    public function index()
    {
        return DumpOrderCategoryResource::collection(DumpOrderCategory::all());
    }

    public function store(DumpOrderCategoryRequest $request)
    {
        $category = DumpOrderCategory::create($request->validated());
        return new DumpOrderCategoryResource($category);
    }

    public function show(DumpOrderCategory $dumpOrderCategory)
    {
        return new DumpOrderCategoryResource($dumpOrderCategory);
    }

    public function update(DumpOrderCategoryRequest $request, DumpOrderCategory $dumpOrderCategory)
    {
        $dumpOrderCategory->update($request->validated());
        return new DumpOrderCategoryResource($dumpOrderCategory);
    }

    public function destroy(DumpOrderCategory $dumpOrderCategory)
    {
        $dumpOrderCategory->delete();
        return response()->noContent();
    }
}

