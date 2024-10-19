<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\PartnerRequest;
use App\Http\Resources\PartnerResource;
use App\Models\Partner;
use Illuminate\Http\Request;

class PartnerController extends Controller
{
    // 一覧を取得
    public function index()
    {
        return PartnerResource::collection(Partner::all());
    }

    // 新規作成
    public function store(PartnerRequest $request)
    {
        $partner = Partner::create($request->validated());
        return new PartnerResource($partner);
    }

    // 詳細取得
    public function show(Partner $partner)
    {
        return new PartnerResource($partner);
    }

    // 更新処理
    public function update(PartnerRequest $request, Partner $partner)
    {
        $partner->update($request->validated());
        return new PartnerResource($partner);
    }

    // 削除処理（ソフトデリート）
    public function destroy(Partner $partner)
    {
        $partner->delete();
        return response()->noContent();
    }
}
