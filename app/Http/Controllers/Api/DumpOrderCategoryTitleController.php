<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\DumpOrderCategoryTitleRequest;
use App\Http\Resources\DumpOrderCategoryTitleResource;
use App\Models\DumpOrderCategoryTitle;
use Illuminate\Http\Request;

class DumpOrderCategoryTitleController extends Controller
{
    // 一覧取得
    public function index()
    {
        return DumpOrderCategoryTitleResource::collection(
            DumpOrderCategoryTitle::all()
        );
    }

    // データ保存
    public function store(DumpOrderCategoryTitleRequest $request)
    {
        $dumpOrderCategoryTitle = DumpOrderCategoryTitle::create($request->validated());
        return new DumpOrderCategoryTitleResource($dumpOrderCategoryTitle);
    }

    // データ詳細取得
    public function show(DumpOrderCategoryTitle $dumpOrderCategoryTitle)
    {
        return new DumpOrderCategoryTitleResource($dumpOrderCategoryTitle);
    }

    // データ更新
    public function update(DumpOrderCategoryTitleRequest $request, DumpOrderCategoryTitle $dumpOrderCategoryTitle)
    {
        $dumpOrderCategoryTitle->update($request->validated());
        return new DumpOrderCategoryTitleResource($dumpOrderCategoryTitle);
    }

    // データ削除
    public function destroy(DumpOrderCategoryTitle $dumpOrderCategoryTitle)
    {
        $dumpOrderCategoryTitle->delete();
        return response()->noContent();
    }
}
