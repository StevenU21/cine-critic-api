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
    Route::get('/users', [UserController::class, 'list']);

    // Admin routes
    Route::middleware('role:admin')->prefix('/admin/users/')->group(function () {
        Route::get('roles', [RoleController::class, 'list']);
        Route::put('{user}/assign-role', [RoleController::class, 'assignRole']);
        Route::get('permissions', [PermissionController::class, 'list']);
        Route::post('{user}/give-permission', [PermissionController::class, 'givePermission']);
        Route::delete('{user}/revoke-permission', [PermissionController::class, 'revokePermission']);
    });
});
