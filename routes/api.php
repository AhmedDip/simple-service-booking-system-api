<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\AdminController;
use App\Http\Controllers\Api\CustomerController;

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

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);

    Route::get('/services', [CustomerController::class, 'listServices']);
    Route::post('/bookings', [CustomerController::class, 'storeBooking']);
    Route::get('/bookings', [CustomerController::class, 'myBookings']);
});

Route::middleware(['auth:sanctum', 'admin'])->group(function () {
    Route::post('/services', [AdminController::class, 'storeService']);
    Route::put('/services/{id}', [AdminController::class, 'updateService']);
    Route::delete('/services/{id}', [AdminController::class, 'deleteService']);

    Route::get('/admin/bookings', [AdminController::class, 'listAllBookings']);
});
