<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use App\Http\Middleware\RoleMiddleware;
use Illuminate\Support\Facades\Route;

Route::prefix('v1/kas-app')->group(function () {
    // Public Routes
    // Route::post('/register', [AuthController::class, 'register']);
    Route::post('/login', [AuthController::class, 'login']);

    // Protected Routes (Middleware)
    Route::middleware('auth:sanctum')->group(function () {
        Route::post('/logout', [AuthController::class, 'logout']);
        Route::get('/users', [UserController::class, 'index'])
            ->middleware([RoleMiddleware::class . ':admin,ketua_kelas']);
    });
});
