<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [\App\Http\Controllers\ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [\App\Http\Controllers\ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [\App\Http\Controllers\ProfileController::class, 'destroy'])->name('profile.destroy');
});


Route::middleware(['auth', 'admin'])->prefix('admin')->group(function () {
    Route::resource('roles', \App\Http\Controllers\Admin\RoleController::class);
    Route::resource('permissions', \App\Http\Controllers\Admin\PermissionController::class);
    Route::get('users/{user}/roles', [\App\Http\Controllers\Admin\UserRoleController::class, 'edit'])
        ->name('admin.users.roles.edit');
    Route::post('users/{user}/roles', [\App\Http\Controllers\Admin\UserRoleController::class, 'update'])
        ->name('admin.users.roles.update');
    Route::get('users/{user}/permissions', [\App\Http\Controllers\Admin\UserPermissionController::class, 'edit'])
        ->name('admin.users.permissions.edit');
    Route::post('users/{user}/permissions', [\App\Http\Controllers\Admin\UserPermissionController::class, 'update'])
        ->name('admin.users.permissions.update');
});

require __DIR__.'/auth.php';

require __DIR__ . '/web_template.php';
require __DIR__ . '/web_setting.php';
