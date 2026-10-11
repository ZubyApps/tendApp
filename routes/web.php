<?php

use App\Http\Controllers\FileController;
use Illuminate\Support\Facades\Route;
use Laravel\Fortify\RoutePath;

Route::inertia('/', 'Welcome')->name('home');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::inertia('dashboard', 'Dashboard')->name('dashboard');
    Route::inertia('import', 'Import')->name('import');
    Route::inertia('pixie', 'Pixie')->name('pixie');

    Route::post(
        RoutePath::for('import', '/import'),
        [FileController::class, 'store']
    );

    Route::inertia('reports', 'Reports')->name('reports');
    Route::inertia('importedFiles', 'ImportedFiles')->name('importedFiles');
    
    // Route::get('/reports', function () {
    // })->name('reports');
});

require __DIR__.'/settings.php';
