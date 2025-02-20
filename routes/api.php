<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdministratorController;
use App\Http\Controllers\ProfileController;

// Administrator routes
Route::post('/administrator/register', [AdministratorController::class, 'register']);
Route::post('/administrator/login', [AdministratorController::class, 'login']);

// Profile routes
Route::middleware('auth:sanctum')->group(function () {
    Route::post('/profiles', [ProfileController::class, 'store']);
    Route::put('/profiles/{profile}', [ProfileController::class, 'update']);
    Route::delete('/profiles/{profile}', [ProfileController::class, 'destroy']);
});

Route::get('/profiles', [ProfileController::class, 'index']);
