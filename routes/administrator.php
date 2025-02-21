<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdministratorController;

Route::prefix('administrator')->group(function () {
    Route::post('/register', [AdministratorController::class, 'register']);
    Route::post('/login', [AdministratorController::class, 'login']);
});
