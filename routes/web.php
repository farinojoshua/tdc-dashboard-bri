<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\DeploymentController;
use App\Http\Controllers\Admin\DeploymentModuleController;
use App\Http\Controllers\Admin\DeploymentServerTypeController;
use App\Http\Controllers\Front\DeploymentController as FrontDeploymentController;

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

Route::get('/deployments', [FrontDeploymentController::class, 'index'])->name('deployments.index');
Route::get('/deployments/calendar', [FrontDeploymentController::class, 'calendar'])->name('deployments.calendar');

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
    ])->group(function () {
        Route::prefix('admin')->name('admin.')->group(function () {
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('admin.dashboard');
        Route::resource('deployment-modules', DeploymentModuleController::class);
        Route::resource('deployment-server-types', DeploymentServerTypeController::class);
        Route::resource('deployments', DeploymentController::class);
        Route::get('deployments/get-server-types-by-module', [DeploymentController::class, 'getServerTypesByModule'])->name('deployments.get-server-types-by-module');
    });
});
