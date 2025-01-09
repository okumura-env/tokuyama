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
use App\Http\Controllers\Api\DumpOrderController;
use App\Http\Controllers\Api\DumpScheduleController;
use App\Http\Controllers\Api\DumpOtherScheduleController;
use App\Http\Controllers\Api\McmTaskTypeController;
use App\Http\Controllers\Api\RuleController;
use App\Http\Controllers\Api\McmCoalUsageScheduleController;



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

//ダンプオーダー
Route::apiResource('dump-orders', DumpOrderController::class);
//ダンプオーダーのインポート
Route::post('/dump-orders/import', [DumpOrderController::class, 'import']);
//富士のオーダー登録
Route::post('/dump-orders/fuji/store', [DumpOrderController::class, 'fujiScheduleStore']);
//MCMルールに基づいたオーダー登録
Route::post('/dump-orders/mcm-rule/store', [DumpOrderController::class, 'mcmRuledScheduleStore']);
//転のオーダー登録
Route::post('/dump-orders/ten/store', [DumpOrderController::class, 'tenScheduleStore']);

//ダンプスケジュール
Route::apiResource('dump-schedules', DumpScheduleController::class);

//その他のスケジュール
Route::apiResource('dump-other-schedules', DumpOtherScheduleController::class);

//MCM運行計画タスクの種類
Route::apiResource('mcm-task-types', McmTaskTypeController::class);

//ルール
Route::apiResource('rules', RuleController::class);

//MCM石炭使用スケジュール
Route::apiResource('mcm-coal-usage-schedules', McmCoalUsageScheduleController::class);