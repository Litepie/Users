<?php

use Illuminate\Support\Facades\Route;
use Litepie\Users\Controllers\UserController;
use Litepie\Users\Controllers\ProfileController;
use Litepie\Users\Controllers\Auth\RegisterController;
use Litepie\Users\Controllers\Auth\LoginController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group.
|
*/

// Authentication Routes
Route::group(['prefix' => 'auth'], function () {
    Route::get('register', [RegisterController::class, 'showRegistrationForm'])->name('users.register');
    Route::post('register', [RegisterController::class, 'register']);
    Route::get('login', [LoginController::class, 'showLoginForm'])->name('users.login');
    Route::post('login', [LoginController::class, 'login']);
    Route::post('logout', [LoginController::class, 'logout'])->name('users.logout');
});

// User Profile Routes
Route::middleware(['auth'])->group(function () {
    Route::get('profile', [ProfileController::class, 'show'])->name('users.profile.show');
    Route::get('profile/edit', [ProfileController::class, 'edit'])->name('users.profile.edit');
    Route::put('profile', [ProfileController::class, 'update'])->name('users.profile.update');
    Route::post('profile/avatar', [ProfileController::class, 'uploadAvatar'])->name('users.profile.avatar');
});

// User Management Routes (for admins)
Route::middleware(['auth', 'role:admin'])->prefix('manage')->group(function () {
    Route::get('users', [UserController::class, 'index'])->name('users.manage.index');
    Route::get('users/create', [UserController::class, 'create'])->name('users.manage.create');
    Route::post('users', [UserController::class, 'store'])->name('users.manage.store');
    Route::get('users/{user}', [UserController::class, 'show'])->name('users.manage.show');
    Route::get('users/{user}/edit', [UserController::class, 'edit'])->name('users.manage.edit');
    Route::put('users/{user}', [UserController::class, 'update'])->name('users.manage.update');
    Route::delete('users/{user}', [UserController::class, 'destroy'])->name('users.manage.destroy');
    
    // User activation/deactivation
    Route::post('users/{user}/activate', [UserController::class, 'activate'])->name('users.manage.activate');
    Route::post('users/{user}/deactivate', [UserController::class, 'deactivate'])->name('users.manage.deactivate');
    Route::post('users/{user}/suspend', [UserController::class, 'suspend'])->name('users.manage.suspend');
    Route::post('users/{user}/ban', [UserController::class, 'ban'])->name('users.manage.ban');
    
    // Role management
    Route::post('users/{user}/roles', [UserController::class, 'assignRole'])->name('users.manage.assign-role');
    Route::delete('users/{user}/roles/{role}', [UserController::class, 'removeRole'])->name('users.manage.remove-role');
});
