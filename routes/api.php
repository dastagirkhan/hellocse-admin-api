<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProfileController;

// Authentication routes
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

// Profile routes
Route::middleware('auth:sanctum')->group(function () {
    Route::post('/profiles', [ProfileController::class, 'store']);
    Route::put('/profiles/{profile}', [ProfileController::class, 'update']);
    Route::delete('/profiles/{profile}', [ProfileController::class, 'destroy']);
});

Route::get('/profiles', [ProfileController::class, 'index']);
