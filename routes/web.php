<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use App\Http\Controllers\TestController;
use App\Http\Controllers\AppController;
use App\Http\Controllers\ReservationController;
use App\Http\Controllers\InpatientController;
use App\Http\Controllers\UserSettingController;
use App\Http\Controllers\MedicalHistoryController;
use App\Http\Controllers\GridLayoutController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/
Route::get('/test', [TestController::class, 'index'])
// ->middleware(['auth', 'verified'])
->name('test');

Route::get('/vuetifydemo', function () {
    return Inertia::render('VuetifyDemo01');
})->middleware(['auth', 'verified'])->name('vuetifydemo');

Route::get('/layout-test', function () {
    return Inertia::render('LayoutTest');
})->middleware(['auth', 'verified'])->name('layout-test');

Route::get('/vuetifydemo02card', function () {
    return Inertia::render('VuetifyDemo02Card');
})->middleware(['auth', 'verified'])->name('vuetifydemo02card');

Route::get('/vuetifydemobuttons', function () {
    return Inertia::render('VuetifyDemoButtons');
})->middleware(['auth', 'verified'])->name('vuetifydemo02buttons');

Route::get('/vuetifydemoselect', function () {
    return Inertia::render('VuetifyDemoSelect');
})->middleware(['auth', 'verified'])->name('vuetifydemoselect');

Route::get('/data-table-items', function () {
    return Inertia::render('DataTableItems');
})->middleware(['auth', 'verified'])->name('data-table-items');

Route::get('/', [AppController::class, 'index'])->name('app');

Route::get('/reservation', [ReservationController::class, 'index'])->name('resetvation');

Route::get('/inpatient', [InpatientController::class, 'index'])->name('inpatient');

// ユーザ設定（初期条件）
Route::get('/user-setting', [UserSettingController::class, 'index'])->name('app');

// 診療履歴
Route::get('/medical-history/{personal}', [MedicalHistoryController::class, 'show'])->name('medical-history.show');

// ダッシュボード グリッドレイアウト
Route::get('/grid-layout', [GridLayoutController::class, 'index'])->name('grid-layout.index');

Route::get('/table-filter-sample', function () {
    return Inertia::render('TableFilterSample');
})->name('table-filter-sample');

ROute::get('/pinia-sample', function () {
    return Inertia::render('PiniaSample');
})->name('pinia-sample');