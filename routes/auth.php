<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;

Route::group(['middleware' => ['guest']], function () {
  // Login
  Route::get('/', [LoginController::class, 'index'])->name('login');
  Route::post('/login', [LoginController::class, 'store'])
    ->middleware(['throttle:login'])
    ->name('login.store');
});

// Logout
Route::post('/logout', [LoginController::class, 'logout'])->middleware(['auth'])->name('logout');
