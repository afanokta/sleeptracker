<?php

use App\Livewire\Drivers\Create;
use App\Livewire\Drivers\Edit;
use App\Livewire\Drivers\Index;

Route::middleware(['auth'])->group(function () {
    Route::prefix('drivers')->group(function () {
        Route::livewire('/', Index::class)->name('driver.index');
        Route::livewire('/new', Create::class)->name('driver.new');
        Route::livewire('/{driver}/edit', Edit::class)->name('driver.edit');
    });
});
