<?php

use Illuminate\Support\Facades\Route;


Route::middleware('auth')->group(function () {

    Route::get('/settings', [SettingController::class, 'index'])->name('settings.index');
    Route::post('/settings/update', [SettingController::class, 'update'])->name('settings.update');
});


// Déconnexion (nécessite une logique POST généralement)
Route::post('/logout', function () {
    auth()->logout();
    return redirect('/login');
})->name('logout');

// end test routes
