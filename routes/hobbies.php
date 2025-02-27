<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HobbyController;

// Routes pour les actions CRUD du HobbyController
Route::prefix('hobbies')->group(function () {
    Route::get('/list', [HobbyController::class, 'index'])->name('hobbies.index');
});


