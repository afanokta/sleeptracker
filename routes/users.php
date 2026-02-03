<?php

use App\Livewire\Users\Create;
use App\Livewire\Users\Edit;
use App\Livewire\Users\Index;

Route::middleware(['auth'])->group(function () {
    Route::prefix('users')->group(function () {
        Route::livewire('/', Index::class)->name('user.index');
        Route::livewire('/new', Create::class)->name('user.new');
        Route::livewire('/{user}/edit', Edit::class)->name('user.edit');
    });
});
