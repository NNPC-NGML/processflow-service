<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UnitController;
use App\Http\Controllers\RoutesController;
use App\Http\Controllers\DepartmentController;
use App\Http\Controllers\DesignationController;
use App\Http\Controllers\ProcessFlowController;
use App\Http\Controllers\ProcessflowStepController;
use App\Http\Controllers\ProcessFlowHistoryController;

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

/**
 * Stores a new process flow.
 */
// Route::middleware(['auth:sanctum'])->group(function () {
// Route::post('processflows', [ProcessFlowController::class, 'store']);
// Route::middleware(['auth:sanctum'])->group(function () {
// Route::post('processflows', [ProcessFlowController::class, 'store']);
// Route::middleware(['auth:sanctum'])->group(function () {


Route::get('/test', [RoutesController::class, 'route']);
Route::middleware('scope.user')->group(function () {
    // Route::post('processflows', [ProcessFlowController::class, 'store']);

    // Route::get('processflows/:id', [ProcessFlowController::class, 'show']);

    Route::post('/processflows', [ProcessFlowController::class, 'store']);
    Route::get('/processflows', [ProcessFlowController::class, 'index']);
    Route::get('/processflows/{id}', [ProcessFlowController::class, 'show']);
    Route::put('/processflows/{id}', [ProcessFlowController::class, 'update']);
    Route::delete('/processflows/{id}', [ProcessFlowController::class, 'destroy']);

    Route::post('/processflowhistory/create', [ProcessFlowHistoryController::class, 'store']);
    Route::get('/processflowhistory', [ProcessFlowHistoryController::class, 'index']);
    Route::get('/processflowhistory/{id}', [ProcessFlowHistoryController::class, 'show']);
    Route::delete('/processflowhistory/{id}', [ProcessFlowHistoryController::class, 'destroy']);

    Route::get('processflowstep', [ProcessflowStepController::class, 'index']);
    Route::post('processflowstep/create/{id}', [ProcessflowStepController::class, 'store']);
    Route::delete('processflowstep/delete/{id}', [ProcessflowStepController::class, 'destroy']);
    Route::put('processflowstep/update/{id}', [ProcessflowStepController::class, 'update']);
    Route::get('processflowstep/view/{id}', [ProcessflowStepController::class, 'show']);
    Route::post('/processflows', [ProcessFlowController::class, 'store']);
    Route::get('/processflows/{id}', [ProcessFlowController::class, 'show']);
    Route::put('/processflows/{id}', [ProcessFlowController::class, 'update']);


    Route::get('/designations', [DesignationController::class, 'index']);
    Route::get('/designations/{id}', [DesignationController::class, 'show']);


    Route::post('/route/create', [RoutesController::class, 'store']);
    Route::get('/route', [RoutesController::class, 'index']);
    Route::get('/route/view/{id}', [RoutesController::class, 'show']);
    Route::put('/route/update/{id}', [RoutesController::class, 'update']);
    Route::delete('/route/delete/{id}', [RoutesController::class, 'destroy']);


    Route::get('/units', [UnitController::class,  'index']);
    Route::get('/units/{id}', [UnitController::class,  'show']);


    Route::get('/departments', [DepartmentController::class,  'index']);
    Route::get('/departments/{id}', [DepartmentController::class,  'show']);
    Route::get('/department_units/{id}', [DepartmentController::class,  'department_units']);
});


Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
