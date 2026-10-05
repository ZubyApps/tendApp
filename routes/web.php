<?php

use App\Http\Controllers\FileController;
use Illuminate\Support\Facades\Route;
use Laravel\Fortify\RoutePath;

Route::inertia('/', 'Welcome')->name('home');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::inertia('dashboard', 'Dashboard')->name('dashboard');



    // Route::inertia('import', 'Import')->name('import');

    Route::post(RoutePath::for('import', '/import'), [FileController::class, 'store']);
        
});

require __DIR__.'/settings.php';
