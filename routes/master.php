<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\PangkatGolonganController;

Route::middleware(['auth'])->group(function () {
    // Role routes
    Route::resource('roles', RoleController::class)->middleware(['can:view roles']);

    // Pangkat golongan routes
    Route::resource('pangkat-golongan', PangkatGolonganController::class)
        ->except(['create', 'show'])
        ->middleware(['can:view pangkat golongan']);

    // User routes
    Route::resource('users', UserController::class)->middleware(['can:view users']);
});