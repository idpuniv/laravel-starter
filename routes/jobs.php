<?php
use Illuminate\Support\Facades\Route;

Route::middleware(['auth'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/job-tracking', [App\Http\Controllers\Admin\JobTrackingController::class, 'index'])->name('job-tracking.index');
    Route::get('/job-tracking/{jobTracking}', [App\Http\Controllers\Admin\JobTrackingController::class, 'show'])->name('job-tracking.show');
    Route::post('/job-tracking/{id}/retry', [App\Http\Controllers\Admin\JobTrackingController::class, 'retry'])->name('job-tracking.retry');
    Route::delete('/job-tracking/{jobTracking}', [App\Http\Controllers\Admin\JobTrackingController::class, 'destroy'])->name('job-tracking.destroy');
});