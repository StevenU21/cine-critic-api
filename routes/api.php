<?php

use App\Http\Controllers\Admin\PermissionController;
use App\Http\Controllers\Admin\RoleController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\GenreController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::post('/register', [RegisterController::class, 'register'])->name('register');
Route::post('/login', [LoginController::class, 'login'])->name('login');

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

    // Genre routes
    Route::put('/genres/{genre}', [GenreController::class, 'update'])->name('genres.update');
    Route::apiResource('/genres', GenreController::class)->except('update');

    // User routes
    Route::get('/users', [UserController::class, 'index']);
    Route::get('/users/{user}', [UserController::class, 'show']);

    // Admin routes
    Route::middleware('role:admin')->prefix('/roles')->group(function () {
        Route::get('', [RoleController::class, 'index']);
        Route::put('/{user}/assign-role', [RoleController::class, 'assignRole']);
        Route::get('/permissions', [PermissionController::class, 'index']);
        Route::get('/permissions/{user}/list-permission', [PermissionController::class, 'getUserPermissions']);
        Route::post('/permissions/{user}/give-permission', [PermissionController::class, 'assignPermission']);
        Route::delete('/permissions/{user}/revoke-permission', [PermissionController::class, 'revokePermission']);
    });
});
