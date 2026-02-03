<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ReportsController;

Route::prefix('laporan')->middleware(['auth', 'can:view reports'])->group(function () {
  // Reports Index
  Route::get('/', [ReportsController::class, 'index'])
    ->name('reports.index');

  // Rekapitulasi Surat Telaahan Staf
  Route::post('/rekapitulasi-surat-telaahan-staf', [ReportsController::class, 'rekapitulasiSuratTelaahanStaf'])
    ->name('reports.rekapitulasi-surat-telaahan-staf');

  // Rekapitulasi Surat Nota Dinas
  Route::post('/rekapitulasi-surat-nota-dinas', [ReportsController::class, 'rekapitulasiSuratNotaDinas'])
    ->name('reports.rekapitulasi-surat-nota-dinas');

  // Rekapitulasi Surat Tugas
  Route::post('/rekapitulasi-surat-tugas', [ReportsController::class, 'rekapitulasiSuratTugas'])
    ->name('reports.rekapitulasi-surat-tugas');

  // Rekapitulasi Aktivitas Pegawai
  Route::post('/rekapitulasi-aktivitas-pegawai', [ReportsController::class, 'rekapitulasiAktivitasPegawai'])
    ->name('reports.rekapitulasi-aktivitas-pegawai');

  // Master Pegawai
  Route::post('/master-pegawai', [ReportsController::class, 'masterPegawai'])
    ->name('reports.master-pegawai');
});
