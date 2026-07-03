<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');


Route::middleware(['auth:sanctum'])->group(function(){
    Route::apiResource('categories', App\Http\Controllers\Api\CategoryController::class);
    Route::post('/settings/update', App\Http\Controllers\Api\SettingController::class)->name('settings.update');
});
