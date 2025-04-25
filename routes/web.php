<?php

use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use App\Http\Controllers\SPAController;

Route::get('/', function () {
    return Inertia::render('Welcome');
})->name('home');

Route::get('dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

require __DIR__.'/settings.php';
require __DIR__.'/auth.php';

require __DIR__.'/message.php';
require __DIR__.'/experiences.php';
require __DIR__.'/education.php';
require __DIR__.'/hobbies.php';
require __DIR__.'/skills.php';

Route::prefix('api/spa')->group(function () {
    Route::get('/list', [SPAController::class, 'index'])->name('spa.index');
});
