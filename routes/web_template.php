<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/components', function () {
    return view('components');
});

Route::get('/dashboard', function () {
    return view('dashboard');
});
