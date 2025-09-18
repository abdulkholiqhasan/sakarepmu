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
        Route::delete('{user}', [App\Http\Controllers\Manage\UserController::class, 'destroy'])->name('destroy');
    });

    Route::prefix('manage/roles')->name('roles.')->group(function () {
        Route::get('/', [App\Http\Controllers\Manage\RoleController::class, 'index'])->name('index');
        Route::get('create', [App\Http\Controllers\Manage\RoleController::class, 'create'])->name('create');
        Route::post('/', [App\Http\Controllers\Manage\RoleController::class, 'store'])->name('store');
        Route::get('{role}/edit', [App\Http\Controllers\Manage\RoleController::class, 'edit'])->name('edit');
        Route::put('{role}', [App\Http\Controllers\Manage\RoleController::class, 'update'])->name('update');
        Route::delete('{role}', [App\Http\Controllers\Manage\RoleController::class, 'destroy'])->name('destroy');
    });

    Route::prefix('manage/permissions')->name('permissions.')->group(function () {
        Route::get('/', [App\Http\Controllers\Manage\PermissionController::class, 'index'])->name('index');
        Route::get('create', [App\Http\Controllers\Manage\PermissionController::class, 'create'])->name('create');
        Route::post('/', [App\Http\Controllers\Manage\PermissionController::class, 'store'])->name('store');
        Route::get('{permission}/edit', [App\Http\Controllers\Manage\PermissionController::class, 'edit'])->name('edit');
        Route::put('{permission}', [App\Http\Controllers\Manage\PermissionController::class, 'update'])->name('update');
        Route::delete('{permission}', [App\Http\Controllers\Manage\PermissionController::class, 'destroy'])->name('destroy');
    });

    // Blog categories for posts (CRUD)
    Route::prefix('manage/posts/categories')->name('categories.')->group(function () {
        Route::get('/', [App\Http\Controllers\Blog\CategoryController::class, 'index'])->name('index');
        Route::get('create', [App\Http\Controllers\Blog\CategoryController::class, 'create'])->name('create');
        Route::post('/', [App\Http\Controllers\Blog\CategoryController::class, 'store'])->name('store');
        Route::get('{category}/edit', [App\Http\Controllers\Blog\CategoryController::class, 'edit'])->name('edit');
        Route::put('{category}', [App\Http\Controllers\Blog\CategoryController::class, 'update'])->name('update');
        Route::delete('{category}', [App\Http\Controllers\Blog\CategoryController::class, 'destroy'])->name('destroy');
    });

    // Blog tags for posts (CRUD)
    Route::prefix('manage/posts/tags')->name('tags.')->group(function () {
        Route::get('/', [App\Http\Controllers\Blog\TagController::class, 'index'])->name('index');
        Route::get('create', [App\Http\Controllers\Blog\TagController::class, 'create'])->name('create');
        Route::post('/', [App\Http\Controllers\Blog\TagController::class, 'store'])->name('store');
        Route::get('{tag}/edit', [App\Http\Controllers\Blog\TagController::class, 'edit'])->name('edit');
        Route::put('{tag}', [App\Http\Controllers\Blog\TagController::class, 'update'])->name('update');
        Route::delete('{tag}', [App\Http\Controllers\Blog\TagController::class, 'destroy'])->name('destroy');
    });
});

require __DIR__ . '/auth.php';
