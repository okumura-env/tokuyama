<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\JetpackDestinationRequest;
use App\Http\Resources\JetpackDestinationResource;
use App\Models\JetpackDestination;

class JetpackDestinationController extends Controller
{
    public function index()
    {
        return JetpackDestinationResource::collection(JetpackDestination::all());
    }

    public function store(JetpackDestinationRequest $request)
    {
        $destination = JetpackDestination::create($request->validated());
        return new JetpackDestinationResource($destination);
    }

    public function show(JetpackDestination $destination)
    {
        return new JetpackDestinationResource($destination);
    }

    public function update(JetpackDestinationRequest $request, JetpackDestination $destination)
    {
        $destination->update($request->validated());
        return new JetpackDestinationResource($destination);
    }

    public function destroy(JetpackDestination $destination)
    {
        $destination->delete();
        return response()->noContent();
    }
}
