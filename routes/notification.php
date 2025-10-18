<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\NotificationController;

Route::middleware(['auth'])->group(function () {
    Route::post('/notifications/{notification}/mark-as-read', [NotificationController::class, 'markAsRead'])
        ->name('notifications.mark-as-read');
});
