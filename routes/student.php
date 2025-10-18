<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MakeupClassController;

Route::get('/makeup-class/confirm/{id}/{email}', [MakeupClassController::class, 'confirmAttendance'])
    ->name('makeup-class.confirm');
Route::get('/makeup-class/decline/{id}/{email}', [MakeupClassController::class, 'showDeclineForm'])
    ->name('makeup-class.decline-form');
Route::post('/makeup-class/decline/{id}/{email}', [MakeupClassController::class, 'declineAttendance'])
    ->name('makeup-class.decline');
