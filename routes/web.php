<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\RecipientController;
use App\Http\Controllers\ConfigController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::middleware('auth')->group(function () {

    // Routes for authenticated users
    Route::get('/dashboard', [DashboardController::class, 'index']);
    Route::get('/emails/{email}', [DashboardController::class, 'show']);

    // Routes for admin users
    Route::middleware('admin')->group(function () {
        Route::post('/recipients', [RecipientController::class, 'store']);
        Route::delete('/recipients/{id}', [RecipientController::class, 'destroy']);
        Route::patch('/recipients/{id}/toggle', [RecipientController::class, 'toggle']);
        Route::post('/recipients/reorder', [RecipientController::class, 'reorder']); // ← drag and drop
        Route::post('/config', [ConfigController::class, 'update']);
        Route::post('/users', [UserController::class, 'store']);
        Route::delete('/users/{id}', [UserController::class, 'destroy']);
        Route::post('/worker/start', [DashboardController::class, 'startWorker']);
        Route::post('/worker/stop', [DashboardController::class, 'stopWorker']);
        Route::post('/worker/check-now', [DashboardController::class, 'checkNow']);
    });

});

require __DIR__.'/auth.php';
