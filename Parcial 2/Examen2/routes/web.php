<?php

use App\Http\Controllers\Admin\InscripcionController as AdminInscripcionController;
use App\Http\Controllers\Admin\TorneoController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\InscripcionController;
use App\Http\Controllers\TorneoController as TorneoPublicoController;
use Illuminate\Support\Facades\Route;

Route::get('/', [TorneoPublicoController::class, 'index'])->name('home');
Route::redirect('/torneos', '/');
Route::get('/torneos/{torneo}', [TorneoPublicoController::class, 'show'])->name('torneos.show');

Route::middleware('guest')->group(function () {
    Route::get('/registro', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/registro', [AuthController::class, 'register']);
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
});

Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    Route::get('/dashboard', [AuthController::class, 'dashboard'])->name('dashboard');
});

Route::middleware('role:admin')->prefix('admin')->name('admin.')->group(function () {
    Route::view('/', 'admin.panel')->name('dashboard');
    Route::resource('torneos', TorneoController::class)->except('show');
    Route::get('/torneos/{torneo}/inscripciones', [AdminInscripcionController::class, 'index'])->name('torneos.inscripciones');
    Route::delete('/inscripciones/{inscripcion}', [AdminInscripcionController::class, 'destroy'])->name('inscripciones.destroy');
});

Route::middleware('role:jugador')->group(function () {
    Route::view('/jugador', 'jugador.panel')->name('jugador.dashboard');
    Route::get('/mis-torneos', [InscripcionController::class, 'index'])->name('mis-torneos');
    Route::post('/torneos/{torneo}/inscripcion', [InscripcionController::class, 'store'])->name('torneos.inscribir');
    Route::delete('/torneos/{torneo}/inscripcion', [InscripcionController::class, 'destroy'])->name('torneos.cancelar');
});
