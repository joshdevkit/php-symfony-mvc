<?php

use App\Http\Controllers\Auth\AuthenticatedSessionController as Auth;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\UserController;
use Core\Route;

Route::get('/', [HomeController::class, 'index']);
Route::get('/about', [HomeController::class, 'about']);

Route::get('/login', [Auth::class, 'login']);
Route::get('/register', [Auth::class, 'register']);
Route::post('/register', [Auth::class, 'create']);

Route::post('/login', [Auth::class, 'execute']);
Route::post('/logout', [Auth::class, 'destroy']);

Route::get('/profile', [UserController::class, 'profile']);
Route::post('/update-profile', [UserController::class, 'update']);
Route::get('/user-info', [UserController::class, 'user_info']);


Route::get('/user/{id}', [UserController::class, 'show']);
Route::post('/user/delete', [UserController::class, 'destroy']);
// Route::controller(UserController::class)->group(function () {
//     Route::get('/user', [UserController::class, 'index']);
//     Route::get('/user/create', 'create');
//     Route::post('/user', 'store');
//     
//     Route::put('/user/{id}', 'update');
//     Route::delete('/user/{id}', 'destroy');
// });
