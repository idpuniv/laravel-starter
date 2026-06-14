<?php 

use Illuminate\Support\Facades\Route;


Route::middleware(['auth'])->prefix('admin')->name('admin.')->group(function () {

         /*
    |--------------------------------------------------------------------------
    | BLOG / ACTUALITÉS
    |--------------------------------------------------------------------------
    */

    Route::resource('posts', App\Http\Controllers\Admin\PostController::class);
    Route::resource('categories', App\Http\Controllers\Admin\CategoryController::class);
    Route::resource('tags', App\Http\Controllers\Admin\TagController::class);
    Route::resource('comments', App\Http\Controllers\CommentController::class)
        ->except(['create', 'store']);
        
});