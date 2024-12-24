<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\RuleRequest;
use App\Http\Resources\RuleResource;
use App\Models\Rule;

class RuleController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     * 
     */
    public function index()
    {
        return RuleResource::collection(Rule::with('mcmTaskType')->get());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param RuleRequest $request
     * @return RuleResource
     * 
     */
    public function store(RuleRequest $request)
    {
        $rule = Rule::create($request->validated());
        return new RuleResource($rule->load('mcmTaskType'));
    }


    /**
     * Display the specified resource.
     *
     * @param Rule $rule
     * @return RuleResource
     */
    public function show(Rule $rule)
    {
        return new RuleResource($rule->load('mcmTaskType'));
    }


    /**
     * Update the specified resource in storage.
     *
     * @param RuleRequest $request
     * @param Rule $rule
     * @return RuleResource
     */
    public function update(RuleRequest $request, Rule $rule)
    {
        $rule->update($request->validated());
        return new RuleResource($rule->load('mcmTaskType'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Rule $rule
     * @return \Illuminate\Http\Response
     */
    public function destroy(Rule $rule)
    {
        $rule->delete();
        return response()->noContent();
    }
}
