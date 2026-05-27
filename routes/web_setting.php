<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SettingController;

Route::middleware('auth:admin,404')->group(function () {
    Route::get('/settings', [SettingController::class, 'index'])->name('settings.index');
    Route::post('/settings/update', [SettingController::class, 'update'])->name('settings.update');
});