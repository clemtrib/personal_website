<?php

use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use App\Http\Controllers\OvhController;
use App\Http\Controllers\SPAController;

/* Starter Laravel + VueJS */

Route::get('/', function () {
    return Inertia::render('Welcome');
})->name('home');

Route::get('dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

require __DIR__ . '/settings.php';
require __DIR__ . '/auth.php';

/* CRUD */
require __DIR__ . '/message.php';
require __DIR__ . '/experiences.php';
require __DIR__ . '/education.php';
require __DIR__ . '/hobbies.php';
require __DIR__ . '/skills.php';
require __DIR__ . '/pages.php';

/* Front: affichage des données */
Route::prefix('api/spa')->group(function () {
    Route::get('/list', [SPAController::class, 'index'])->name('spa.index');
});

/* OVH: gestion des images */
Route::get('uploads-ovh/{filename}', [OvhController::class, 'getFile']);

/* OVH: déploiement */
Route::prefix('deploy')->group(function () {
    Route::get('/run-migrations', [OvhController::class, 'runMigrations']);
    Route::get('/clear-cache', [OvhController::class, 'clearCache']);
    Route::get('/storage-link', [OvhController::class, 'storageLink']);
});
