<?php 
use Illuminate\Support\Facades\Route;

Route::middleware(['auth'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/audit', [App\Http\Controllers\Admin\AuditController::class, 'index'])->name('audit.index');
    Route::get('/audit/{auditLog}', [App\Http\Controllers\Admin\AuditController::class, 'show'])->name('audit.show');
    Route::delete('/audit/{auditLog}', [App\Http\Controllers\Admin\AuditController::class, 'destroy'])->name('audit.destroy');
    Route::post('/audit/clear-old', [App\Http\Controllers\Admin\AuditController::class, 'clearOld'])->name('audit.clear-old');
    Route::get('/audit/export/json', [App\Http\Controllers\Admin\AuditController::class, 'export'])->name('audit.export');
});