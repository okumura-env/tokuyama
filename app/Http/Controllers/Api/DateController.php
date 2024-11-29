<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\DateRequest;
use App\Http\Resources\DateResource;
use App\Models\Date;

class DateController extends Controller
{
    // 全件取得
    public function index()
    {
        return DateResource::collection(Date::all());
    }

    // 新規作成
    public function store(DateRequest $request)
    {
        $date = Date::create($request->validated());
        return new DateResource($date);
    }

    // 単一取得
    public function show(Date $date)
    {
        return new DateResource($date);
    }
}
