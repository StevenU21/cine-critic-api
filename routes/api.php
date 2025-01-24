<?php

use App\Http\Controllers\Admin\PermissionController;
use App\Http\Controllers\Admin\RoleController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\DirectorController;
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

    // Director routes
    Route::put('/directors/{director}', [DirectorController::class, 'update'])->name('directors.update');
    Route::apiResource('/directors', DirectorController::class)->except(['update']);

    // User routes
    Route::get('/users', [UserController::class, 'index'])->name('users.index');
    Route::get('/users/{user}', [UserController::class, 'show'])->name('users.show');

    // Admin routes
    Route::middleware('role:admin')->prefix('/roles')->name('roles.')->group(function () {
        Route::get('', [RoleController::class, 'index'])->name('index');
        Route::put('/{user}/assign-role', [RoleController::class, 'assignRole'])->name('assign-role');
        Route::get('/permissions', [PermissionController::class, 'index'])->name('permissions.index');
        Route::get('/permissions/{user}/list-permission', [PermissionController::class, 'getUserPermissions'])->name('permissions.list-permission');
        Route::post('/permissions/{user}/give-permission', [PermissionController::class, 'assignPermission'])->name('permissions.give-permission');
        Route::delete('/permissions/{user}/revoke-permission', [PermissionController::class, 'revokePermission'])->name('permissions.revoke-permission');
    });
});
