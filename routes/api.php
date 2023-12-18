<?php

use App\Http\Controllers\Admin\BackgroundJobsMonitoring\BackgroundJobController;
use App\Http\Controllers\Admin\Deployment\DeploymentController;
use App\Http\Controllers\Front\Deployment\DeploymentController as FrontDeploymentController;
use App\Http\Controllers\Front\BackgroundJobsMonitoring\BackgroundJobController as FrontBackgroundJobController;
use App\Http\Controllers\Front\UserManagement\UserManagementController as FrontUserManagementController;
use App\Http\Controllers\Front\Brisol\BrisolController as FrontBrisolController;
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
Route::get('/modules/{module_id}/server-types/{selectedServerTypeId?}', [DeploymentController::class, 'getServerTypesByModule']);
Route::get('/deployments/events', [FrontDeploymentController::class, 'getEvents']);
Route::get('/deployments/chart-data', [FrontDeploymentController::class, 'getChartData']);

// background jobs monitoring
Route::get('/bjm/get-processes-by-type', [BackgroundJobController::class, 'getProcessesByType']);
Route::get('/bjm/get-background-jobs-daily', [FrontBackgroundJobController::class, 'getBackgroundJobs']);

// user management
Route::get('/usman/get-request-by-type-chart', [FrontUserManagementController::class, 'getRequestByType']);
Route::get('/usman/get-sla-category-chart', [FrontUserManagementController::class, 'getSLADataForYear']);
Route::get('/usman/get-top-kanwil-request-chart', [FrontUserManagementController::class, 'getTopKanwilRequests']);
Route::get('/usman/get-monthly-target-actual', [FrontUserManagementController::class, 'getMonthlyDataTargetActual']);

// brisol
Route::get('/brisol/get-service-ci-chart', [FrontBrisolController::class, 'getServiceCIChart']);
Route::get('/brisol/get-slm-status-chart', [FrontBrisolController::class, 'getSLMStatusChart']);
Route::get('/brisol/get-reported-source-chart', [FrontBrisolController::class, 'getReportedSource']);
Route::get('/brisol/get-monthly-target-actual', [FrontBrisolController::class, 'getMonthlyDataTargetActual']);
Route::get('/brisol/get-service-ci-top-issue', [FrontBrisolController::class, 'getServiceCITopIssueChart']);
Route::get('/brisol/get-overall-top-issue', [FrontBrisolController::class, 'getOverallTopIssueChart']);
