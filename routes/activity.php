<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ActivityContoller;

Route::middleware(['auth'])->group(function () {
    Route::get('/activity-logs', [ActivityContoller::class, 'index'])
        ->middleware(['can:view activity log'])
        ->name('activity-logs.index');
});