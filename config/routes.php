<?php

use App\Controllers\AdminController;
use App\Controllers\AuthController;
use App\Controllers\HomeController;
use Core\Router\Route;

Route::get('/login', [AuthController::class, 'login'])->name('users.login');
Route::post('/login', [AuthController::class, 'authenticate'])->name('users.authenticate');

Route::get('/admin/login', [AuthController::class, 'adminLogin'])->name('admins.login');
Route::post('/admin/login', [AuthController::class, 'adminAuthenticate'])->name('admins.authenticate');

Route::middleware('auth')->group(function () {
    Route::get('/', [HomeController::class, 'index'])->name('users.root');
    Route::get('/logout', [AuthController::class, 'destroy'])->name('users.logout');
});

Route::middleware('admin')->group(function () {
    Route::get('/admin', [AdminController::class, 'index'])->name('admins.root');
    Route::get('/admin/logout', [AuthController::class, 'adminDestroy'])->name('admins.logout');
    Route::get('/admin/users', [AdminController::class, 'users'])->name('admins.users');
});
