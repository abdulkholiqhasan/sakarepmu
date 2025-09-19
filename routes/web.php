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
    Volt::route('settings/general', 'settings.general')->name('general.edit');
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
        // AJAX search endpoint for categories (used by post forms)
        Route::get('search', [App\Http\Controllers\Blog\CategoryController::class, 'search'])->name('search');
        Route::get('/', [App\Http\Controllers\Blog\CategoryController::class, 'index'])->name('index');
        Route::get('create', [App\Http\Controllers\Blog\CategoryController::class, 'create'])->name('create');
        Route::post('/', [App\Http\Controllers\Blog\CategoryController::class, 'store'])->name('store');
        Route::get('{category}/edit', [App\Http\Controllers\Blog\CategoryController::class, 'edit'])->name('edit');
        Route::put('{category}', [App\Http\Controllers\Blog\CategoryController::class, 'update'])->name('update');
        Route::delete('{category}', [App\Http\Controllers\Blog\CategoryController::class, 'destroy'])->name('destroy');
    });

    // Blog tags for posts (CRUD)
    Route::prefix('manage/posts/tags')->name('tags.')->group(function () {
        // AJAX search endpoint for tags (used by post forms)
        Route::get('search', [App\Http\Controllers\Blog\TagController::class, 'search'])->name('search');
        Route::get('/', [App\Http\Controllers\Blog\TagController::class, 'index'])->name('index');
        Route::get('create', [App\Http\Controllers\Blog\TagController::class, 'create'])->name('create');
        Route::post('/', [App\Http\Controllers\Blog\TagController::class, 'store'])->name('store');
        Route::get('{tag}/edit', [App\Http\Controllers\Blog\TagController::class, 'edit'])->name('edit');
        Route::put('{tag}', [App\Http\Controllers\Blog\TagController::class, 'update'])->name('update');
        Route::delete('{tag}', [App\Http\Controllers\Blog\TagController::class, 'destroy'])->name('destroy');
    });

    // Blog posts (CRUD)
    Route::prefix('manage/posts/posts')->name('posts.')->group(function () {
        Route::get('/', [App\Http\Controllers\Blog\PostController::class, 'index'])->name('index');
        Route::get('create', [App\Http\Controllers\Blog\PostController::class, 'create'])->name('create');
        Route::post('/', [App\Http\Controllers\Blog\PostController::class, 'store'])->name('store');
        Route::get('{post}/edit', [App\Http\Controllers\Blog\PostController::class, 'edit'])->name('edit');
        Route::put('{post}', [App\Http\Controllers\Blog\PostController::class, 'update'])->name('update');
        Route::delete('{post}', [App\Http\Controllers\Blog\PostController::class, 'destroy'])->name('destroy');
    });

    // Blog pages (CRUD)
    Route::prefix('manage/pages')->name('pages.')->group(function () {
        Route::get('/', [App\Http\Controllers\Blog\PagesController::class, 'index'])->name('index');
        Route::get('create', [App\Http\Controllers\Blog\PagesController::class, 'create'])->name('create');
        Route::post('/', [App\Http\Controllers\Blog\PagesController::class, 'store'])->name('store');
        Route::get('{page}', [App\Http\Controllers\Blog\PagesController::class, 'show'])->name('show');
        Route::get('{page}/edit', [App\Http\Controllers\Blog\PagesController::class, 'edit'])->name('edit');
        Route::put('{page}', [App\Http\Controllers\Blog\PagesController::class, 'update'])->name('update');
        Route::delete('{page}', [App\Http\Controllers\Blog\PagesController::class, 'destroy'])->name('destroy');
    });

    // Media library (CRUD)
    Route::prefix('manage/media')->name('media.')->group(function () {
        Route::get('/', [App\Http\Controllers\MediaController::class, 'index'])->name('index');
        Route::get('create', [App\Http\Controllers\MediaController::class, 'create'])->name('create');
        Route::post('/', [App\Http\Controllers\MediaController::class, 'store'])->name('store');
        Route::get('{media}', [App\Http\Controllers\MediaController::class, 'show'])->name('show');
        Route::get('{media}/edit', [App\Http\Controllers\MediaController::class, 'edit'])->name('edit');
        Route::put('{media}', [App\Http\Controllers\MediaController::class, 'update'])->name('update');
        Route::delete('{media}', [App\Http\Controllers\MediaController::class, 'destroy'])->name('destroy');
    });
});

require __DIR__ . '/auth.php';

// Lightweight JSON endpoint for username availability used by the registration page JS fallback.
Route::get('/username/check', function (\Illuminate\Http\Request $request) {
    $username = \Illuminate\Support\Str::lower((string) $request->query('username', ''));
    if (trim($username) === '') {
        return response()->json(['status' => '', 'suggestions' => []]);
    }

    $validator = \Illuminate\Support\Facades\Validator::make(['username' => $username], ['username' => ['required', 'string', 'max:50', 'alpha_dash']]);
    if ($validator->fails()) {
        return response()->json(['status' => 'invalid', 'suggestions' => []]);
    }

    $exists = App\Models\User::where('username', $username)->exists();
    $status = $exists ? 'taken' : 'available';

    $suggestions = [];
    if ($exists) {
        $suffixes = ['hub', 'jpg', '702', 'app', 'xyz'];
        foreach ($suffixes as $s) {
            $candidate = preg_match('/^\d+$/', $s) ? $username . $s : ($username . '-' . $s);
            if (!App\Models\User::where('username', $candidate)->exists()) {
                $suggestions[] = $candidate;
            }
            if (count($suggestions) >= 3) break;
        }
        $i = 1;
        while (count($suggestions) < 3 && $i <= 50) {
            $candidate = $username . $i;
            if (!App\Models\User::where('username', $candidate)->exists()) {
                $suggestions[] = $candidate;
            }
            $i++;
        }
    }

    return response()->json(['status' => $status, 'suggestions' => $suggestions]);
});
