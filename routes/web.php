<?php

use Laravel\Jetstream\Rules\Role;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\Deployment\DeploymentController;
use App\Http\Controllers\Admin\Deployment\DeploymentModuleController;
use App\Http\Controllers\Admin\Deployment\DeploymentServerTypeController;
use App\Http\Controllers\Admin\BackgroundJobsMonitoring\ProcessController;
use App\Http\Controllers\Admin\BackgroundJobsMonitoring\BackgroundJobController;
use App\Http\Controllers\Front\Deployment\DeploymentController as FrontDeploymentController;
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
    return view('welcome');
});

Route::get('/deployments/chart', [FrontDeploymentController::class, 'index'])->name('deployments.index');
Route::get('/deployments/calendar', [FrontDeploymentController::class, 'calendar'])->name('deployments.calendar');
Route::get('/background-jobs-monitoring/daily', [FrontBackgroundJobController::class, 'daily'])->name('background-jobs-monitoring.daily');
Route::get('/background-jobs-monitoring/data-amount', [FrontBackgroundJobController::class, 'showDataAmountCharts'])->name('background-jobs-monitoring.data-amount');
Route::get('/background-jobs-monitoring/duration', [FrontBackgroundJobController::class, 'showDurationCharts'])->name('background-jobs-monitoring.duration');

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
    ])->group(function () {
        Route::prefix('admin')->name('admin.')->group(function () {
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
        Route::prefix('deployments')->name('deployments.')->group(function () {
            Route::resource('server-types', DeploymentServerTypeController::class);
            Route::resource('modules', DeploymentModuleController::class);
            Route::resource('deployment', DeploymentController::class);
        });
        Route::prefix('background-jobs-monitoring')->name('background-jobs-monitoring.')->group(function () {
            Route::resource('processes', ProcessController::class);
            Route::resource('jobs', BackgroundJobController::class);
        });
    });
});
