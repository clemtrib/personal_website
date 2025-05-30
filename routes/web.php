<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Session;
use Inertia\Inertia;
use App\Http\Controllers\OvhController;
use App\Http\Controllers\SPAController;
use App\Http\Controllers\GoogleAuthController;

use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Redirect;

use App\Services\GoogleMeetService;

/* Starter Laravel + VueJS */

Route::get('/', [SPAController::class, 'load'])->name('home');

Route::get('dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

require __DIR__ . '/settings.php';
require __DIR__ . '/auth.php';

/* CRUD */
require __DIR__ . '/messages.php';
require __DIR__ . '/experiences.php';
require __DIR__ . '/education.php';
require __DIR__ . '/hobbies.php';
require __DIR__ . '/skills.php';
require __DIR__ . '/pages.php';
require __DIR__ . '/meets.php';
require __DIR__ . '/customers.php';
require __DIR__ . '/bills.php';

/* Front: affichage des données */
Route::prefix('api/spa')->group(function () {
    Route::get('/list', [SPAController::class, 'index'])->name('spa.index');
    Route::get('/timeslots/{date}', [SPAController::class, 'getMeetingTimeslots'])->name('spa.timeslots');
});

/* OVH: gestion des images */
Route::get('uploads-ovh/{filename}', [OvhController::class, 'getFile']);

/* OVH: déploiement */
Route::prefix('deploy')->group(function () {
    Route::get('/run-migrations', [OvhController::class, 'runMigrations']);
    Route::get('/clear-cache', [OvhController::class, 'clearCache']);
    Route::get('/storage-link', [OvhController::class, 'storageLink']);
});

Route::get('/google/redirect', [GoogleAuthController::class, 'redirect']);
Route::get('/auth/google/callback', [GoogleAuthController::class, 'callback'])->name('google.callback');

Route::get('/auth/google', function (GoogleMeetService $meet): RedirectResponse {
    return Redirect::away($meet->getAuthUrl());
})->name('google.auth');
