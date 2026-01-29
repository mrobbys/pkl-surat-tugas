<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ActivityContoller;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\PangkatGolonganController;
use App\Http\Controllers\SuratPerjalananDinasController;

require __DIR__ . '/auth.php';

Route::middleware(['auth'])->group(function () {
  // dashboard route
  Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
  Route::get('/dashboard/calendar', [DashboardController::class, 'calendarData'])->name('dashboard.calendar');

  // role routes
  Route::resource('roles', RoleController::class)->middleware(['can:view roles']);

  // pangkat golongan routes
  Route::resource('pangkat-golongan', PangkatGolonganController::class)->except(['create', 'show'])->middleware(['can:view pangkat golongan']);

  // user routes
  Route::resource('users', UserController::class)->middleware(['can:view users']);

  // activity log routes
  Route::get("/activity-logs", [ActivityContoller::class, 'index'])->middleware(['can:view activity log'])->name('activity-logs.index');

  // surat perjalanan dinas routes
  // index
  Route::get('/surat', [SuratPerjalananDinasController::class, 'indexSurat'])->middleware(['can:view telaah staf'])->name('surat.index');
  // create telaah staf
  Route::get('/surat/telaah-staf/create', [SuratPerjalananDinasController::class, 'createTelaahStaf'])->middleware(['can:create telaah staf'])->name('telaah-staf.create');
  // store telaah staf
  Route::post('/telaah-staf', [SuratPerjalananDinasController::class, 'storeTelaahStaf'])->name('telaah-staf.store');
  // show telaah staf
  Route::get('/surat/telaah-staf/{surat}', [SuratPerjalananDinasController::class, 'showTelaahStaf'])->name('telaah-staf.show');
  // edit telaah staf
  Route::get('/surat/telaah-staf/{surat}/edit', [SuratPerjalananDinasController::class, 'editTelaahStaf'])->middleware(['can:edit telaah staf'])->name('telaah-staf.edit');
  // update telaah staf
  Route::put('/telaah-staf/{surat}', [SuratPerjalananDinasController::class, 'updateTelaahStaf'])->name('telaah-staf.update');
  // destroy
  Route::delete('/telaah-staf/{surat}', [SuratPerjalananDinasController::class, 'destroyTelaahStaf'])->name('telaah-staf.destroy');
  // approve satu telaah staf
  Route::put('/telaah-staf/{surat}/approve', [SuratPerjalananDinasController::class, 'approveSatuTelaahStaf'])->name('telaah-staf.approve-satu');
  // approve dua telaah staf
  Route::put('/telaah-staf/{surat}/approve-dua', [SuratPerjalananDinasController::class, 'approveDuaTelaahStaf'])->name('telaah-staf.approve-dua');
  // cetak pdf surat telaah staf
  Route::get('/surat/{surat}/cetak-telaah-staf', [SuratPerjalananDinasController::class, 'cetakPDFTelaahStaf'])->name('telaah-staf.cetak');
  // cetak pdf surat nota dinas
  Route::get('/surat/{surat}/cetak-nota-dinas', [SuratPerjalananDinasController::class, 'cetakPDFNotaDinas'])->name('telaah-staf.cetak-nota-dinas');
  // cetak pdf surat tugas
  Route::get('/surat/{surat}/cetak-surat-tugas', [SuratPerjalananDinasController::class, 'cetakPDFSuratTugas'])->name('telaah-staf.cetak-surat-tugas');
});
