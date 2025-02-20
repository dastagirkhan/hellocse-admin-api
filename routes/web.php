<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/api/documentation', function () {
    return view('swagger-ui');
});

Route::view('/profiles', 'profiles.index')->name('profiles.index');

Route::get('/api-documentation', function () {
    return view('api-documentation');
});
