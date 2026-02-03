<?php

use App\Livewire\Monitorings\Edit;
use App\Livewire\Monitorings\Index;

Route::middleware(['auth'])->group(function () {
    Route::prefix('monitoring')->group(function () {
        Route::livewire('/', Index::class)->name('monitoring.index');
        Route::livewire('/{monitoring}/edit', Edit::class)->name('monitoring.edit');
    });
});
