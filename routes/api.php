<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\DataTableItemApiController;
use App\Http\Controllers\Api\CalcChartDataApiController;
use App\Http\Controllers\Api\CalcSituationDataApiController;
use App\Http\Controllers\Api\ReservationApiController;
use App\Http\Controllers\Api\InpatientApiController;
use App\Http\Controllers\Api\SearchConditionApiController;
use App\Http\Controllers\Api\CalcPatientCheckApiController;
use App\Http\Controllers\Api\CostPatientApiController;
/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
// test
Route::get('data-table-item', [DataTableItemApiController::class, 'index'])->name('data-table-item');

// 初期検索条件更新
Route::
post('/update-search-condition', [SearchConditionApiController::class, 'updateSearchCondition'])
->name('api.update-search-condition');

// 算定チャート
// 日次データ取得
Route::
get('/calc-chart-data', [CalcChartDataApiController::class, 'index'])
->name('api.calc-chart-data');
// 月次データ取得
Route::
get('/calc-chart-term-month-data', [CalcChartDataApiController::class, 'getTermMonthData'])
->name('api.calc-chart-term-month-data');

// 算定状況
Route::
get('/calc-situation-data', [CalcSituationDataApiController::class, 'index'])
->name('api.calc-situation-data');

// 外来予約リスト
Route::
    // middleware('auth:sanctum')
// ->
get('/reservations', [ReservationApiController::class, 'index'])
->name('api.reservations');
// 在院患者リスト
Route::
get('/inpatients', [InpatientApiController::class, 'index'])
->name('api.inpatients');

// 算定チェック
Route::
post('/calc-patient-check', [CalcPatientCheckApiController::class, 'calcPatientCheck'])
->name('api.calc-patient-check');

// 外来コスト
Route::
get('/outpatient-cost', [CostPatientApiController::class, 'getOutpatientCost'])
->name('api.outpatient-cost');