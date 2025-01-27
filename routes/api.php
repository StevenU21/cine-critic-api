<?php

use App\Http\Controllers\Admin\PermissionController;
use App\Http\Controllers\Admin\RoleController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\DirectorController;
use App\Http\Controllers\GenreController;
use App\Http\Controllers\MovieController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\ReviewController;
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

    // Movie routes
    Route::put('/movies/{movie}', [MovieController::class, 'update'])->name('movies.update');
    Route::apiResource('/movies', MovieController::class)->except(['update']);

    // General reviews routes
    Route::get('/reviews', [ReviewController::class, 'general_index'])->name('reviews.general.index');
    Route::get('/reviews/{review}', [ReviewController::class, 'general_show'])->name('reviews.general.show');

    // Review routes
    Route::prefix('/reviews')->group(function () {
        Route::get('/movies/{movie}', [ReviewController::class, 'index'])->name('reviews.index');
        Route::post('/movies/{movie}', [ReviewController::class, 'store'])->name('reviews.store');
        Route::put('/movies/{movie}/{review}', [ReviewController::class, 'update'])->name('reviews.update');
        Route::delete('/{review}', [ReviewController::class, 'destroy'])->name('reviews.destroy');
    });

    // Notification routes
    Route::prefix('/notifications')->name('notifications.')->group(function () {
        Route::get('', [NotificationController::class, 'index'])->name('index');
        Route::get('/{notification}', [NotificationController::class, 'show'])->name('show');
        Route::put('/{notification}/mark-as-read', [NotificationController::class, 'markAsRead'])->name('markAsRead');
        Route::put('/mark-all-as-read', [NotificationController::class, 'markAllAsRead'])->name('markAllAsRead');
        Route::delete('/{notification}', [NotificationController::class, 'destroy'])->name('destroy');
        Route::delete('', [NotificationController::class, 'destroyAll'])->name('destroyAll');
    });

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
