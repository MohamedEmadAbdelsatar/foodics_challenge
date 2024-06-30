<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::post('/register', [\App\Http\Controllers\AuthController::class, 'register'])->name('register');
Route::post('/login', [\App\Http\Controllers\AuthController::class, 'login'])->name('login');

Route::post('/orders', [\App\Http\Controllers\OrderController::class, 'store'])->middleware('auth:sanctum');
