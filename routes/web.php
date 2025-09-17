<?php

use Illuminate\Support\Facades\Route;
use Livewire\Volt\Volt;

Route::get('/', function () {
    return view('welcome');
})->name('home');

Route::view('dashboard', 'dashboard')
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::middleware(['auth'])->group(function () {
    Route::redirect('settings', 'settings/profile');

    Volt::route('settings/profile', 'settings.profile')->name('profile.edit');
    Volt::route('settings/password', 'settings.password')->name('password.edit');
    Volt::route('settings/appearance', 'settings.appearance')->name('appearance.edit');
    // User management routes
    // These are standard controllers so the sidebar helper (Route::has('users.index')) can resolve them.
    Route::prefix('manage/users')->name('users.')->group(function () {
        Route::get('/', [App\Http\Controllers\Manage\UserController::class, 'index'])->name('index');
        Route::get('create', [App\Http\Controllers\Manage\UserController::class, 'create'])->name('create');
        Route::post('/', [App\Http\Controllers\Manage\UserController::class, 'store'])->name('store');
        Route::get('{user}/edit', [App\Http\Controllers\Manage\UserController::class, 'edit'])->name('edit');
        Route::put('{user}', [App\Http\Controllers\Manage\UserController::class, 'update'])->name('update');
    });
});

require __DIR__ . '/auth.php';
