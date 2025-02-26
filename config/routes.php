<?php

use App\Controllers\AdminController;
use App\Controllers\AuthController;
use App\Controllers\HomeController;
use App\Controllers\PositionController;
use App\Controllers\WorkerController;
use Core\Router\Route;

// Funcionario login
Route::get('/login', [AuthController::class, 'login'])->name('users.login');
Route::post('/login', [AuthController::class, 'authenticate'])->name('users.authenticate');

// Admin login
Route::get('/admin/login', [AuthController::class, 'adminLogin'])->name('admins.login');
Route::post('/admin/login', [AuthController::class, 'adminAuthenticate'])->name('admins.authenticate');

// Middleware Funcionario logado
Route::middleware('auth')->group(function () {
    Route::get('/', [HomeController::class, 'index'])->name('users.root');
    Route::get('/logout', [AuthController::class, 'destroy'])->name('users.logout');
});

// Middleware admin logado
Route::middleware('admin')->group(function () {

    // Index
    Route::get('/admin', [AdminController::class, 'index'])->name('admins.root');

    // Logout
    Route::get('/admin/logout', [AuthController::class, 'adminDestroy'])->name('admins.logout');

    // Retrieve
    Route::get('/admin/admins', [AdminController::class, 'admins'])->name('admins.admins');
    Route::get('/admin/workers', [WorkerController::class, 'workers'])->name('workers.workers');
    Route::get('/admin/positions', [PositionController::class, 'positions'])->name('positions.positions');
    Route::get('/admin/admins/page/{page}', [AdminController::class, 'admins'])->name('admins.paginate');
    Route::get('/admin/workers/page/{page}', [WorkerController::class, 'workers'])->name('workers.paginate');
    Route::get('/admin/positions/page/{page}', [PositionController::class, 'positions'])->name('positions.paginate');

    // Create
    Route::get('/admin/admins/create', [AdminController::class, 'create'])->name('admins.create');
    Route::post('/admin/admins/create', [AdminController::class, 'store'])->name('admins.store');
    Route::get('/admin/workers/create', [WorkerController::class, 'create'])->name('workers.create');
    Route::post('/admin/workers/create', [WorkerController::class, 'store'])->name('workers.store');
    Route::post('/admin/positions/create', [PositionController::class, 'store'])->name('positions.store');
    Route::get('/admin/positions/create', [PositionController::class, 'create'])->name('positions.create');

    // Update
    Route::get('/admin/admins/{id}/edit', [AdminController::class, 'edit'])->name('admins.edit');
    Route::get('/admin/workers/{id}/edit', [WorkerController::class, 'edit'])->name('workers.edit');
    Route::put('/admin/admins/{id}', [AdminController::class, 'update'])->name('admins.update');
    Route::put('/admin/workers/{id}', [WorkerController::class, 'update'])->name('workers.update');

    // Delete
    Route::delete('/admin/admins/delete/{id}', [AdminController::class, 'destroy'])->name('admins.destroy');
    Route::delete('/admin/workers/delete/{id}', [WorkerController::class, 'destroy'])->name('workers.destroy');
    Route::delete('/admin/positions/delete/{id}', [PositionController::class, 'destroy'])->name('positions.destroy');
});
