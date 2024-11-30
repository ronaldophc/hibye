<?php

use App\Controllers\AuthController;
use App\Controllers\HomeController;
use Core\Router\Route;

Route::get('/', [HomeController::class, 'index'])->name('root');


Route::get('/login', [AuthController::class, 'index'])->name('users.login');
Route::post('/login', [AuthController::class, 'authenticate'])->name('users.authenticate');
