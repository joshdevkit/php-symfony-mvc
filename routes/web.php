<?php

use App\Http\Controllers\Auth\AuthenticatedSessionController as Auth;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\UserController;
use Core\Route;

Route::get('/', [HomeController::class, 'index']);
Route::get('/about', [HomeController::class, 'about']);

Route::get('/user/{id}', [UserController::class, 'index']);


Route::get('/login', [Auth::class, 'login']);
Route::get('/register', [Auth::class, 'register']);
Route::post('/register', [Auth::class, 'create']);


Route::post('/login', [Auth::class, 'execute']);
Route::post('/logout', [Auth::class, 'destroy']);


Route::get('/profile', [UserController::class, 'profile']);
