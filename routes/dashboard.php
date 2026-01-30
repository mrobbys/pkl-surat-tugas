<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;

Route::middleware(['auth'])->group(function () {
  // Dashboard route
  Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

  // Dashboard statistik status pengajuan surat menggunakan chart.js
  Route::get('/dashboard/status-statistics', [DashboardController::class, 'statusStatistics'])->name('dashboard.status-statistics');

  // Dashboard statistik proporsi penugasan pegawai berdasarkan golongan menggunakan chart.js
  Route::get('/dashboard/golongan-statistics', [DashboardController::class, 'golonganStatistics'])->name('dashboard.golongan-statistics');

  // Calendar menggunakan fullcalendar.
  Route::get('/dashboard/calendar', [DashboardController::class, 'calendarData'])->name('dashboard.calendar');
});
