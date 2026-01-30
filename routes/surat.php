<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SuratPerjalananDinasController;

Route::middleware(['auth'])->group(function () {
    // Index
    Route::get('/surat', [SuratPerjalananDinasController::class, 'indexSurat'])
        ->middleware(['can:view telaah staf'])
        ->name('surat.index');

    // Telaah Staf CRUD
    Route::get('/surat/telaah-staf/create', [SuratPerjalananDinasController::class, 'createTelaahStaf'])
        ->middleware(['can:create telaah staf'])
        ->name('telaah-staf.create');

    Route::post('/telaah-staf', [SuratPerjalananDinasController::class, 'storeTelaahStaf'])
        ->name('telaah-staf.store');

    Route::get('/surat/telaah-staf/{surat}', [SuratPerjalananDinasController::class, 'showTelaahStaf'])
        ->name('telaah-staf.show');

    Route::get('/surat/telaah-staf/{surat}/edit', [SuratPerjalananDinasController::class, 'editTelaahStaf'])
        ->middleware(['can:edit telaah staf'])
        ->name('telaah-staf.edit');

    Route::put('/telaah-staf/{surat}', [SuratPerjalananDinasController::class, 'updateTelaahStaf'])
        ->name('telaah-staf.update');

    Route::delete('/telaah-staf/{surat}', [SuratPerjalananDinasController::class, 'destroyTelaahStaf'])
        ->name('telaah-staf.destroy');

    // Approval
    Route::put('/telaah-staf/{surat}/approve', [SuratPerjalananDinasController::class, 'approveSatuTelaahStaf'])
        ->name('telaah-staf.approve-satu');

    Route::put('/telaah-staf/{surat}/approve-dua', [SuratPerjalananDinasController::class, 'approveDuaTelaahStaf'])
        ->name('telaah-staf.approve-dua');

    // Cetak PDF
    Route::get('/surat/{surat}/cetak-telaah-staf', [SuratPerjalananDinasController::class, 'cetakPDFTelaahStaf'])
        ->name('telaah-staf.cetak');

    Route::get('/surat/{surat}/cetak-nota-dinas', [SuratPerjalananDinasController::class, 'cetakPDFNotaDinas'])
        ->name('telaah-staf.cetak-nota-dinas');

    Route::get('/surat/{surat}/cetak-surat-tugas', [SuratPerjalananDinasController::class, 'cetakPDFSuratTugas'])
        ->name('telaah-staf.cetak-surat-tugas');
});