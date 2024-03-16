<?php

use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\ComplaintController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\RoleController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth:sanctum'])->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('/employees', [RegisteredUserController::class, 'store'])
    ->middleware('guest')
    ->name('register');

Route::post('/login', [AuthenticatedSessionController::class, 'store'])
    // ->middleware('guest')
    ->name('login');

Route::post('customers', [CustomerController::class, 'create']);
Route::get('customers', [CustomerController::class, 'index']);

Route::get('employees', [EmployeeController::class, 'index']);

Route::post('complaints', [ComplaintController::class, 'create']);
Route::get('complaints', [ComplaintController::class, 'index']);
Route::put('complaints/{complaint}', [ComplaintController::class, 'allocateToEmployee']);

Route::get('roles', [RoleController::class, 'index']);

require __DIR__ . '/auth.php';
