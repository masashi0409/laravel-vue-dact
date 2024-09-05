<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Foundation\Application;
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
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
});

Route::get('/dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';

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