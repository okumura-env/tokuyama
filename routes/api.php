<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Api\PartnerController;
use App\Http\Controllers\Api\DateController;
use App\Http\Controllers\Api\WorkerController;
use App\Http\Controllers\Api\VehicleTypeController;
use App\Http\Controllers\Api\VehicleController;
use App\Http\Controllers\Api\DumpOrderCategoryController;
use App\Http\Controllers\Api\DumpOrderCategoryTitleController;
use App\Http\Controllers\Api\WorkTypeController;
use App\Http\Controllers\Api\DailyVehicleAssignmentController;
use App\Http\Controllers\Api\DumpOrderController;
use App\Http\Controllers\Api\ImportDumpOrderController;
use App\Http\Controllers\Api\DumpScheduleController;
use App\Http\Controllers\Api\DumpOtherScheduleController;



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

//車両
Route::apiResource('vehicles', VehicleController::class);

//ダンプオーダーの種類
Route::apiResource('dump-order-categories', DumpOrderCategoryController::class);

//ダンプオーダーのタイトル
Route::apiResource('dump-order-category-titles', DumpOrderCategoryTitleController::class);

//作業の種類
Route::apiResource('work-types', WorkTypeController::class);

//日別車両割り当て
Route::apiResource('daily-vehicle-assignments', DailyVehicleAssignmentController::class);

//ダンプオーダー
Route::apiResource('dump-orders', DumpOrderController::class);

//ファイルのアップロード
Route::post('/import-dump-orders', [ImportDumpOrderController::class, 'import']);

//ダンプスケジュール
Route::apiResource('dump-schedules', DumpScheduleController::class);

//その他のスケジュール
Route::apiResource('dump-other-schedules', DumpOtherScheduleController::class);