<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/users', [UserManagementController::class, 'apiIndex']);
    Route::get('/user/{user}', [UserManagementController::class, 'apiShow']);
    Route::get('/stats', [DashboardController::class, 'apiStats']);
});
