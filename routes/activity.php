<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ActivityController;

Route::middleware(['auth'])->group(function () {
    Route::get('/activity-logs', [ActivityController::class, 'index'])
        ->middleware(['can:view activity log'])
        ->name('activity-logs.index');
});