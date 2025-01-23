<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\JetpackOrderRequest;
use App\Http\Resources\JetpackOrderResource;
use App\Models\JetpackOrder;

class JetpackOrderController extends Controller
{
    public function index()
    {
        return JetpackOrderResource::collection(JetpackOrder::all());
    }

    public function store(JetpackOrderRequest $request)
    {
        $order = JetpackOrder::create($request->validated());
        return new JetpackOrderResource($order);
    }

    public function show(JetpackOrder $order)
    {
        return new JetpackOrderResource($order);
    }

    public function update(JetpackOrderRequest $request, JetpackOrder $order)
    {
        $order->update($request->validated());
        return new JetpackOrderResource($order);
    }

    public function destroy(JetpackOrder $order)
    {
        $order->delete();
        return response()->noContent();
    }

    public function fetchJetpackOrdersByDate($date)
    {
        $jetpackOrders = JetpackOrder::where('date_id', $date)->get(); 
        return JetpackOrderResource::collection($jetpackOrders);
    }
}
