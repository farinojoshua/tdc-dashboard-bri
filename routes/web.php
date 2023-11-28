<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\Deployment\DeploymentController;
use App\Http\Controllers\Admin\Deployment\DeploymentModuleController;
use App\Http\Controllers\Admin\Deployment\DeploymentServerTypeController;
use App\Http\Controllers\Admin\BackgroundJobsMonitoring\ProcessController;
use App\Http\Controllers\Admin\BackgroundJobsMonitoring\BackgroundJobController;
use App\Http\Controllers\Front\Brisol\BrisolController as FrontBrisolController;
use App\Http\Controllers\Admin\Brisol\IncidentsController as BrisolIncidentsController;
use App\Http\Controllers\Front\Deployment\DeploymentController as FrontDeploymentController;
use App\Http\Controllers\Admin\UserManagement\IncidentsController as UsmanIncidentsController;
use App\Http\Controllers\Admin\Brisol\MonthlyTargetController as BrisolMonthlyTargetController;
use App\Http\Controllers\Admin\UserManagement\MonthlyTargetController as UsmanMonthlyTargetController;
use App\Http\Controllers\Front\UserManagement\UserManagementController as FrontUserManagementController;
use App\Http\Controllers\Front\BackgroundJobsMonitoring\BackgroundJobController as FrontBackgroundJobController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('front.user-management.user-management-request');
});

Route::get('/deployments/chart', [FrontDeploymentController::class, 'index'])->name('deployments.index');
Route::get('/deployments/calendar', [FrontDeploymentController::class, 'calendar'])->name('deployments.calendar');
Route::get('/background-jobs-monitoring/daily', [FrontBackgroundJobController::class, 'daily'])->name('background-jobs-monitoring.daily');
Route::get('/background-jobs-monitoring/data-amount', [FrontBackgroundJobController::class, 'showDataAmountCharts'])->name('background-jobs-monitoring.data-amount');
Route::get('/background-jobs-monitoring/duration', [FrontBackgroundJobController::class, 'showDurationCharts'])->name('background-jobs-monitoring.duration');
Route::get('/user-management/request-by-type', [FrontUserManagementController::class, 'showRequestByTypeChart'])->name('user-management.request-by-type');
Route::get('/user-management/sla-category', [FrontUserManagementController::class, 'showSLACategoryChart'])->name('user-management.sla-category');
Route::get('/user-management/top-branch', [FrontUserManagementController::class, 'showTopBranchRequestsChart'])->name('user-management.top-branch');
Route::get('/user-management/monthly-target', [FrontUserManagementController::class, 'showMonthlyDataTargetActualChart'])->name('user-management.monthly-target');
Route::get('/brisol/service-ci', [FrontBrisolController::class, 'showServiceCIChart'])->name('brisol.service-ci');
Route::get('/brisol/slm-status', [FrontBrisolController::class, 'showSLMStatusChart'])->name('brisol.slm-status');
Route::get('/brisol/reported-source', [FrontBrisolController::class, 'showReportedSourceChart'])->name('brisol.reported-source');
Route::get('/brisol/monthly-target', [FrontBrisolController::class, 'showMonthlyDataTargetActualChart'])->name('brisol.monthly-target');
Route::get('/brisol/service-ci-top-issue', [FrontBrisolController::class, 'showServiceCITopIssueChart'])->name('brisol.service-ci-top-issue');

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
    ])->group(function () {
        Route::prefix('admin')->name('admin.')->group(function () {
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
        Route::prefix('deployments')->name('deployments.')->group(function () {
            Route::resource('deployment', DeploymentController::class)->middleware('permission:manage deployments');
            Route::resource('modules', DeploymentModuleController::class)->middleware('permission:manage module deployments');
            Route::resource('server-types', DeploymentServerTypeController::class)->middleware('permission:manage server type deployments');

            // only view module deployments and server type deployments
            Route::get('/modules', [DeploymentModuleController::class, 'index'])->name('modules.index')->middleware('permission:view module deployments');
            Route::get('/server-types', [DeploymentServerTypeController::class, 'index'])->name('server-types.index')->middleware('permission:view server type deployments');
        });

        Route::prefix('background-jobs-monitoring')->name('background-jobs-monitoring.')->group(function () {
            Route::resource('processes', ProcessController::class)->middleware('permission:manage process background jobs');
            Route::resource('jobs', BackgroundJobController::class)->middleware('permission:manage background jobs');

            // only view process background jobs
            Route::get('/processes', [ProcessController::class, 'index'])->name('processes.index')->middleware('permission:view process background jobs');
        });

        Route::prefix('user-management')->name('user-management.')->group(function () {
            Route::resource('incidents', UsmanIncidentsController::class)->middleware('permission:manage incidents user management');
            Route::resource('monthly-target', UsmanMonthlyTargetController::class)->middleware('permission:manage monthly target user management');

            // only view incidents user management and monthly target user management
            Route::get('/monthly-target', [UsmanMonthlyTargetController::class, 'index'])->name('monthly-target.index')->middleware('permission:view monthly target user management');
        });

        Route::prefix('brisol')->name('brisol.')->group(function () {
            Route::resource('incidents', BrisolIncidentsController::class)->middleware('permission:manage incidents brisol');
            Route::resource('monthly-target', BrisolMonthlyTargetController::class)->middleware('permission:manage monthly target brisol');

            // only view incidents brisol and monthly target brisol
            Route::get('/monthly-target', [BrisolMonthlyTargetController::class, 'index'])->name('monthly-target.index')->middleware('permission:view monthly target brisol');
        });

        Route::resource('users', UserController::class)->middleware('permission:manage users');
    });
});
