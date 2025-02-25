<?php

use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use App\Http\Controllers\MessageController;

// Route pour afficher le formulaire de contact
Route::get('contact', function () {
    return Inertia::render('Contact');
})->name('contact');

Route::post('contact', [MessageController::class, 'store'])->name('store');

// Route pour afficher la liste de messages
Route::get('/dashboard/messages', function () {
    return Inertia::render('Messages');
})->name('messages');

// Routes pour les actions CRUD du MessageController
Route::prefix('messages')->group(function () {
    Route::get('/list', [MessageController::class, 'index'])->name('messages.index');
    Route::post('/', [MessageController::class, 'store'])->name('messages.store');
    Route::get('/{message}', [MessageController::class, 'show'])->name('messages.show');
    Route::put('/{message}', [MessageController::class, 'update'])->name('messages.update');
    Route::delete('/{message}', [MessageController::class, 'destroy'])->name('messages.destroy');
});


