<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\JetpackDestinationRouteRequest;
use App\Http\Resources\JetpackDestinationRouteResource;
use App\Models\JetpackDestinationRoute;

class JetpackDestinationRouteController extends Controller
{
    public function index()
    {
        return JetpackDestinationRouteResource::collection(JetpackDestinationRoute::all());
    }

    public function store(JetpackDestinationRouteRequest $request)
    {
        $route = JetpackDestinationRoute::create($request->validated());
        return new JetpackDestinationRouteResource($route);
    }

    public function show(JetpackDestinationRoute $route)
    {
        return new JetpackDestinationRouteResource($route);
    }

    public function update(JetpackDestinationRouteRequest $request, JetpackDestinationRoute $route)
    {
        $route->update($request->validated());
        return new JetpackDestinationRouteResource($route);
    }

    public function destroy(JetpackDestinationRoute $route)
    {
        $route->delete();
        return response()->noContent();
    }
}
