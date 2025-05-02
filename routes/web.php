<?php

use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use App\Http\Controllers\SPAController;
use Illuminate\Support\Facades\Artisan;


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
require __DIR__.'/pages.php';

Route::prefix('api/spa')->group(function () {
    Route::get('/list', [SPAController::class, 'index'])->name('spa.index');
});



Route::get('/run-migrations', function (\Illuminate\Http\Request $request) {
    // Protection par token
    $token = $request->query('token');

    if ($token !== env('MIGRATION_WEBHOOK_TOKEN')) {
        abort(403, 'Unauthorized');
    }

    // Exécute les migrations en production (sans confirmation)
    Artisan::call('migrate', ['--force' => true]);

    return response()->json([
        'status' => 'success',
        'output' => Artisan::output()
    ]);
});
