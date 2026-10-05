<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\RecetaController;
use Illuminate\Support\Facades\Route;

Route::redirect('/', '/recetas');

Route::middleware('guest')->group(function () {
    Route::get('/registro', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/registro', [AuthController::class, 'register']);
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->middleware('throttle:10,1');
});

Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    Route::resource('recetas', RecetaController::class)->whereNumber('receta');
});
