<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Api\PartnerController;
use App\Http\Controllers\Api\DateController;
use App\Http\Controllers\Api\WorkerController;
use App\Http\Controllers\Api\VehicleTypeController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

//協力業者
Route::apiResource('partners', PartnerController::class);

//日付
Route::apiResource('dates', DateController::class);

//従業員
Route::apiResource('workers', WorkerController::class);

//車両種別
Route::apiResource('vehicle-types', VehicleTypeController::class);