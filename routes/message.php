<?php

use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use App\Http\Controllers\MessageController;

// Route pour afficher la liste de messages
Route::post('message', [MessageController::class, 'store'])->name('messages.store');

// Routes pour les actions CRUD du MessageController
Route::prefix('dashboard/messages')->middleware('auth')->group(function () {
    Route::get('/list', function () {
        return Inertia::render('Messages');
    })->name('messages');
    Route::get('/list/{page}', [MessageController::class, 'index'])->name('messages.index');
    Route::delete('/{message}', [MessageController::class, 'destroy'])->name('messages.destroy');
});
