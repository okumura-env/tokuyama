<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\WorkerRequest;
use App\Http\Resources\WorkerResource;
use App\Models\Worker;
use Illuminate\Http\Request;

class WorkerController extends Controller
{
    public function index()
    {
        return WorkerResource::collection(Worker::all());
    }

    public function store(WorkerRequest $request)
    {
        $worker = Worker::create($request->validated());
        return new WorkerResource($worker);
    }

    public function show(Worker $worker)
    {
        return new WorkerResource($worker);
    }

    public function update(WorkerRequest $request, Worker $worker)
    {
        $worker->update($request->validated());
        return new WorkerResource($worker);
    }

    public function destroy(Worker $worker)
    {
        $worker->delete();
        return response()->noContent();
    }
}
