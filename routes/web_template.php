<?php

use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/components', function () {
    return view('components');
});

/*
|------------------------------------------------------------
| Maquettes HTML statiques (resources/views/templates)
|------------------------------------------------------------
*/
Route::get('/templates/{file?}', function (string $file = 'index') {
    $file = basename($file, '.html');
    $path = resource_path("views/templates/{$file}.html");

    abort_unless(File::exists($path), 404);

    return response(File::get($path));
})->where('file', '[A-Za-z0-9_-]+(\.html)?')->name('templates.show');

