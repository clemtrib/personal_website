<?php

use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use App\Http\Controllers\MessageController;

// Route pour afficher la liste de messages
Route::post('message', [MessageController::class, 'store'])->name('messages.store');

// Routes pour les actions CRUD du MessageController
Route::prefix('dashboard/messages')->middleware('auth')->group(function () {
    Route::get('/list', [MessageController::class, 'index'])->name('messages');
    Route::delete('/{message}', [MessageController::class, 'destroy'])->name('messages.destroy');
});
