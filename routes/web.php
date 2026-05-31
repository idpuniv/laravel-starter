<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('dashboard', [App\Http\Controllers\DashboardController::class, 'index'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [App\Http\Controllers\ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [App\Http\Controllers\ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [App\Http\Controllers\ProfileController::class, 'destroy'])->name('profile.destroy');
});


Route::middleware(['auth','teams'])->prefix('admin')->name('admin.')->group(function () {


    Route::get('dashboard', [App\Http\Controllers\Admin\DashboardController::class, 'index'])->name('dashboard');
    Route::resource('users', App\Http\Controllers\Admin\UserController::class);
    Route::resource('people', App\Http\Controllers\PersonController::class);
    Route::livewire('/admin/people', 'pages::post.create');
    Route::post('people/{person}/add-user', [App\Http\Controllers\PersonController::class, 'addUser'])->name('people.add-user');
    Route::get('people/{person}/add-user', [App\Http\Controllers\PersonController::class, 'showAddUserForm'])->name('people.show-add-user-form');
    Route::patch('users/{user}/status', [App\Http\Controllers\Admin\UserController::class, 'changeStatus'])->name('users.change-status');
    Route::resource('roles', App\Http\Controllers\Admin\RoleController::class);

    Route::resource('permissions', App\Http\Controllers\Admin\PermissionController::class)
        ->except(['create', 'store', 'destroy']);

    Route::delete('/admin/users/{id}/force', [App\Http\Controllers\Admin\UserController::class, 'forceDestroy'])
        ->name('admin.users.force-destroy');

    /*
    |--------------------------------------------------------------------------
    | ROLES USER
    |--------------------------------------------------------------------------
    */

    Route::get('users/roles', [App\Http\Controllers\Admin\UserRoleController::class, 'index'])
        ->name('users.roles.index');

    Route::get('users/{user}/roles', [App\Http\Controllers\Admin\UserRoleController::class, 'edit'])
        ->name('users.roles.edit');

    Route::put('users/{user}/roles', [App\Http\Controllers\Admin\UserRoleController::class, 'update'])
        ->name('users.roles.update');

    Route::get('users/{user}/roles/show', [App\Http\Controllers\Admin\UserRoleController::class, 'show'])
        ->name('users.roles.show');

    /*
    |--------------------------------------------------------------------------
    | PERMISSIONS USER
    |--------------------------------------------------------------------------
    */

    Route::get('users/{user}/permissions', [App\Http\Controllers\Admin\UserPermissionController::class, 'edit'])
        ->name('users.permissions.edit');

    Route::put('users/{user}/permissions', [App\Http\Controllers\Admin\UserPermissionController::class, 'update'])
        ->name('users.permissions.update');


    Route::resource('teams', App\Http\Controllers\Admin\TeamController::class);
    // Route::resource('teams.users', App\Http\Controllers\Admin\TeamUserController::class);
    // Route::resource('users.teams', App\Http\Controllers\Admin\UserTeamController::class);
     Route::prefix('teams/{team}')->name('teams.')->group(function () {
            Route::resource('users', App\Http\Controllers\Admin\TeamUserController::class)->only([
                'index', 'create', 'store', 'destroy'
            ]);

            // Route::resource('roles', TeamRoleController::class)->only([
            //     'index', 'store', 'destroy'
            // ]);
        });
    
    
});

require __DIR__ . '/auth.php';
require __DIR__ . '/notifications.php';
require __DIR__ . '/jobs.php';
require __DIR__ . '/audit.php';


require __DIR__ . '/web_template.php';
require __DIR__ . '/web_setting.php';
