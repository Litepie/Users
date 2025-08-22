<?php

use Illuminate\Support\Facades\Route;
use Litepie\Users\Controllers\Api\UserController;
use Litepie\Users\Controllers\Api\ProfileController;
use Litepie\Users\Controllers\Api\AuthController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group.
|
*/

// Authentication Routes
Route::prefix('auth')->group(function () {
    Route::post('register', [AuthController::class, 'register']);
    Route::post('login', [AuthController::class, 'login']);
    Route::post('logout', [AuthController::class, 'logout'])->middleware('auth:sanctum');
    Route::post('refresh', [AuthController::class, 'refresh'])->middleware('auth:sanctum');
    Route::get('me', [AuthController::class, 'me'])->middleware('auth:sanctum');
});

// Protected API Routes
Route::middleware(['auth:sanctum'])->group(function () {
    
    // Profile Management
    Route::prefix('profile')->group(function () {
        Route::get('/', [ProfileController::class, 'show']);
        Route::put('/', [ProfileController::class, 'update']);
        Route::post('avatar', [ProfileController::class, 'uploadAvatar']);
        Route::delete('avatar', [ProfileController::class, 'removeAvatar']);
        Route::get('activity', [ProfileController::class, 'activity']);
    });
    
    // User Management (Admin/Manager access)
    Route::middleware(['role:admin|manager'])->prefix('users')->group(function () {
        Route::get('/', [UserController::class, 'index']);
        Route::post('/', [UserController::class, 'store']);
        Route::get('/{user}', [UserController::class, 'show']);
        Route::put('/{user}', [UserController::class, 'update']);
        Route::delete('/{user}', [UserController::class, 'destroy']);
        
        // User Status Management
        Route::post('/{user}/activate', [UserController::class, 'activate']);
        Route::post('/{user}/deactivate', [UserController::class, 'deactivate']);
        Route::post('/{user}/suspend', [UserController::class, 'suspend']);
        Route::post('/{user}/ban', [UserController::class, 'ban']);
        
        // Role Management
        Route::get('/{user}/roles', [UserController::class, 'getRoles']);
        Route::post('/{user}/roles', [UserController::class, 'assignRole']);
        Route::delete('/{user}/roles/{role}', [UserController::class, 'removeRole']);
        
        // Permissions
        Route::get('/{user}/permissions', [UserController::class, 'getPermissions']);
        Route::post('/{user}/permissions', [UserController::class, 'givePermission']);
        Route::delete('/{user}/permissions/{permission}', [UserController::class, 'revokePermission']);
        
        // User Statistics and Analytics
        Route::get('/{user}/activity', [UserController::class, 'getActivity']);
        Route::get('/{user}/login-attempts', [UserController::class, 'getLoginAttempts']);
        Route::get('/{user}/sessions', [UserController::class, 'getSessions']);
    });
    
    // User Statistics (Admin only)
    Route::middleware(['role:admin'])->prefix('statistics')->group(function () {
        Route::get('users', [UserController::class, 'getUserStatistics']);
        Route::get('registrations', [UserController::class, 'getRegistrationStats']);
        Route::get('activity', [UserController::class, 'getActivityStats']);
        Route::get('user-types', [UserController::class, 'getUserTypeStats']);
    });
    
    // Bulk Operations (Admin only)
    Route::middleware(['role:admin'])->prefix('bulk')->group(function () {
        Route::post('users/activate', [UserController::class, 'bulkActivate']);
        Route::post('users/deactivate', [UserController::class, 'bulkDeactivate']);
        Route::post('users/delete', [UserController::class, 'bulkDelete']);
        Route::post('users/assign-role', [UserController::class, 'bulkAssignRole']);
        Route::post('users/export', [UserController::class, 'exportUsers']);
    });
});
