<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\RecipientController;
use App\Http\Controllers\ConfigController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\EmailController;
use Illuminate\Support\Facades\Route;

// Route for authenticated users
Route::middleware('auth')->group(function () {

    // Routes for authenticated users
    Route::redirect('/', '/dashboard');
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/emails', [EmailController::class, 'index'])->name('emails.index');
    Route::get('/emails/{email}', [DashboardController::class, 'show'])->name('emails.show');
    Route::get('/recipients', [RecipientController::class, 'index'])->name('recipients.index');


    Route::get('/api/stats', function () {
        return response()->json([
            'total'     => \App\Models\Email::count(),
            'forwarded' => \App\Models\Email::where('status', 'forwarded')->count(),
            'pending'   => \App\Models\Email::where('status', 'pending')->count(),
            'errors'    => \App\Models\Email::where('status', 'error')->count(),
        ]);
    });

    Route::get('/api/next-recipient', function () {
        $state      = \App\Models\RotationState::firstOrCreate(['id' => 1], ['current_index' => 0]);
        $recipients = \App\Models\Recipient::active()->get();

        if ($recipients->isEmpty()) {
            return response()->json(['id' => null]);
        }

        $idx       = $state->current_index % $recipients->count();
        $recipient = $recipients[$idx];

        return response()->json([
            'id'    => $recipient->id,
            'name'  => $recipient->name,
            'email' => $recipient->email,
        ]);
    });

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
