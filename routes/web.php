<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\DeploymentController;
use App\Http\Controllers\Admin\DeploymentModuleController;
use App\Http\Controllers\Admin\DeploymentServerTypeController;

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

Route::get('/deployments', function () {
    return view('front.deployments');
});

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    Route::prefix('admin')->name('admin.')->group(function () {
        Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
        Route::resource('deployment-modules', DeploymentModuleController::class);
        Route::resource('deployment-server-types', DeploymentServerTypeController::class);
        Route::resource('deployments', DeploymentController::class);
        Route::get('deployments/get-server-types-by-module', [DeploymentController::class, 'getServerTypesByModule'])->name('deployments.get-server-types-by-module');
    });
});
