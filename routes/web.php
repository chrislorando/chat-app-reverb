<?php

use Illuminate\Support\Facades\Route;

Route::view('/', 'welcome');

Route::view('dashboard', 'dashboard')
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::view('profile', 'profile')
    ->middleware(['auth'])
    ->name('profile');

// Broadcast::routes();

// Broadcast::routes(['middleware' => ['auth']]); 
// Broadcast::routes(['middleware' => ['auth:api']]);

require __DIR__.'/auth.php';
