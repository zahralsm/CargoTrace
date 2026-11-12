<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\ShipmentApiController;
use Illuminate\Support\Facades\Route;

// 1. Public Endpoints (Tanpa Token)
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

// 2. Protected Endpoints (Wajib Bearer Token Sanctum)
Route::middleware('auth:sanctum')->group(function () {
    // Auth info & Logout
    Route::get('/user', [AuthController::class, 'user']);
    Route::post('/logout', [AuthController::class, 'logout']);

    // Master Resources (Slide 12)
    Route::get('/carriers', [ShipmentApiController::class, 'carriers']);
    Route::get('/locations', [ShipmentApiController::class, 'locations']);

    // Shipments CRUD & Tracking Endpoints (Slide 12)
    Route::get('/shipments', [ShipmentApiController::class, 'index']);
    Route::post('/shipments', [ShipmentApiController::class, 'store']);
    Route::get('/shipments/{id}', [ShipmentApiController::class, 'show']);
    Route::get('/shipments/{id}/tracking', [ShipmentApiController::class, 'tracking']);
    Route::post('/shipments/{id}/tracking', [ShipmentApiController::class, 'addTracking']);
});