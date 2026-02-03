<?php

use Illuminate\Support\Facades\Route;

Route::view('dashboard', 'dashboard')
    ->middleware(['auth'])
    ->name('dashboard');

Route::get('/', \App\Livewire\SleepMonitoring\Form::class)
    ->name('sleep-monitoring.form');

require __DIR__.'/settings.php';
require __DIR__.'/drivers.php';
require __DIR__.'/users.php';
require __DIR__.'/datas.php';
require __DIR__.'/sleeptracks.php';
