<?php

use App\Livewire\Settings\Profile;
use App\Livewire\Settings\Password;
use App\Livewire\Settings\Appearance;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
})->name('home');

Route::view('dashboard', 'dashboard')
    ->middleware(['auth', 'verified'])
    ->name('dashboard');



Route::middleware(['auth'])->group(function () {
    Route::middleware(['role:admin'])->group(function () {
        // devices
        Route::get('devices', App\Livewire\Devices\Index::class)->name('devices');    
    });
    
    // transactions
    Route::get('transactions', App\Livewire\Transactions\Index::class)->name('transactions');
    Route::get('transactions/create', App\Livewire\Transactions\Create::class)->name('transactions.create');

    Route::get('transactions/callback/{code}', App\Livewire\Transactions\Callback::class)->name('transactions.callback');

    // settings
    Route::redirect('settings', 'settings/profile');
    Route::get('settings/profile', Profile::class)->name('settings.profile');
    Route::get('settings/password', Password::class)->name('settings.password');
    Route::get('settings/appearance', Appearance::class)->name('settings.appearance');
});

require __DIR__.'/auth.php';
