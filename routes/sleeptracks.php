<?php

use App\Livewire\Sleeptracks\Index;
use App\Livewire\Sleeptracks\Edit;
use App\Http\Controllers\SleeptrackController;

Route::middleware(['auth'])->group(function () {
    Route::prefix('sleeptracks')->group(function () {
        Route::livewire('/', Index::class)->name('sleeptrack.index');
        Route::livewire('/{sleepReport}/edit', Edit::class)->name('sleeptrack.edit');
        Route::get('/export/pdf', [SleeptrackController::class, 'exportPdf'])->name('sleeptrack.export.pdf');
        Route::get('/export/excel', [SleeptrackController::class, 'exportExcel'])->name('sleeptrack.export.excel');
    });
});


