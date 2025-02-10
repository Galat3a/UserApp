<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\UserManagementController;

Auth::routes(['verify' => true]);

Route::get('/', [App\Http\Controllers\HomeController::class, 'home'])->name('home');

// Rutas protegidas por autenticación
Route::middleware(['auth'])->group(function () {
    // Cambiar 'profile.edit' a 'profile.show' para mantener consistencia
    Route::get('/profile', [ProfileController::class, 'show'])->name('profile.show');
    Route::post('/profile/password', [ProfileController::class, 'changePassword'])->name('profile.changePassword');
    Route::post('/profile/email', [ProfileController::class, 'changeEmail'])->name('profile.changeEmail');
    Route::post('/profile/username', [ProfileController::class, 'changeUsername'])->name('profile.changeUsername');
    Route::delete('/profile/delete', [ProfileController::class, 'deleteUser'])->name('profile.deleteUser');
    Route::post('/profile/change-role', [ProfileController::class, 'changeRole'])->name('profile.changeRole');
    
    // Rutas para administración de usuarios
    Route::get('/user/create', [UserManagementController::class, 'create'])->name('users.create'); // Añadida ruta create
    Route::post('/user', [UserManagementController::class, 'store'])->name('users.store');
    Route::put('/user/{user}/verify', [UserManagementController::class, 'verify'])->name('users.verify');
});

// Rutas para administración de usuarios
Route::middleware(['auth', \App\Http\Middleware\AdminMiddleware::class])->group(function () {
    Route::get('/users', [UserManagementController::class, 'index'])->name('users.index');
    Route::get('/users/{user}/edit', [UserManagementController::class, 'edit'])->name('users.edit');
    Route::put('/users/{user}', [UserManagementController::class, 'update'])->name('users.update');
    Route::delete('/users/{user}', [UserManagementController::class, 'destroy'])->name('users.destroy');
});
