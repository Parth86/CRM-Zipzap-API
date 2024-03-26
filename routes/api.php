<?php

use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\ComplaintController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\QueryController;
use App\Http\Controllers\RoleController;
use Illuminate\Support\Facades\Route;

Route::post('/login', [AuthenticatedSessionController::class, 'store'])
    // ->middleware('guest')
    ->name('login');

Route::get('roles', [RoleController::class, 'index']);

Route::middleware(['auth:sanctum'])->group(function () {
    Route::get('/user', function () {
        return auth()->user();
    });

    Route::get('dashboard', [DashboardController::class, 'index']);

    Route::post('/employees', [RegisteredUserController::class, 'store']);

    Route::post('/logout', [AuthenticatedSessionController::class, 'destroy']);

    Route::post('customers', [CustomerController::class, 'create']);
    Route::get('customers', [CustomerController::class, 'index']);

    Route::get('employees', [EmployeeController::class, 'index']);

    Route::post('complaints', [ComplaintController::class, 'create']);
    Route::get('complaints', [ComplaintController::class, 'index']);
    Route::put('complaints/{complaint}', [ComplaintController::class, 'allocateToEmployee']);
    Route::patch('complaints/{complaint}', [ComplaintController::class, 'completeComplaint']);

    Route::post('queries', [QueryController::class, 'create']);
    Route::get('queries', [QueryController::class, 'index']);
    Route::get('queries/{query}', [QueryController::class, 'view']);
    Route::put('queries/{query}', [QueryController::class, 'addComments']);
    Route::patch('queries/{query}', [QueryController::class, 'completeQuery']);

    require __DIR__.'/auth.php';
});
