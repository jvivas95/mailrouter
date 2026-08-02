<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DashboardController;
use Illuminate\Support\Facades\Route;

Route::middleware('auth')->group(function () {

    // Routes for authenticated users
    Route::get('/', [DashboardController::class, 'index']);
    Route::get('/emails/{email}', [DashboardController::class, 'show']);

    // Routes for admin users
    Route::middleware('admin')->group(function () {

    });

});

require __DIR__.'/auth.php';
