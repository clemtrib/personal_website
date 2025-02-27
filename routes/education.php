<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\EducationController;

// Routes pour les actions CRUD du EducationController
Route::prefix('education')->group(function () {
    Route::get('/list', [EducationController::class, 'index'])->name('education.index');
});


