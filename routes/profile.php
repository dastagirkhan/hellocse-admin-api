<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;

Route::get('/profiles', [ProfileController::class, 'index']);

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/profiles', [ProfileController::class, 'store']);
    Route::put('/profiles/{profile}', [ProfileController::class, 'update']);
    Route::delete('/profiles/{profile}', [ProfileController::class, 'destroy']);
});
