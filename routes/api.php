<?php

use App\Http\Controllers\Admin\BackgroundJobsMonitoring\BackgroundJobController;
use App\Http\Controllers\Admin\Deployment\DeploymentController;
use App\Http\Controllers\Front\Deployment\DeploymentController as FrontDeploymentController;
use App\Http\Controllers\Front\BackgroundJobsMonitoring\BackgroundJobController as FrontBackgroundJobController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

// deployments
Route::get('/modules/{module_id}/server-types', [DeploymentController::class, 'getServerTypesByModule']);
Route::get('/deployments/events', [DeploymentController::class, 'getEvents']);
Route::get('/deployments/chart-data', [FrontDeploymentController::class, 'getChartData']);

// background jobs monitoring
Route::get('/get-processes-by-type', [BackgroundJobController::class, 'getProcessesByType']);
Route::get('/background-jobs/get-data-amount', [FrontBackgroundJobController::class, 'getChartDataAmount']);
Route::get('/get-background-jobs-daily', [FrontBackgroundJobController::class, 'getBackgroundJobs']);
