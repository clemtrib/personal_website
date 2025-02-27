<?php

use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use App\Http\Controllers\WorkExperienceController;

// Routes pour les actions CRUD du WorkExperienceController
Route::prefix('experiences')->group(function () {
    Route::get('/list', [WorkExperienceController::class, 'index'])->name('experiences.index');
});

// Route pour afficher la liste de messages
Route::get('/dashboard/experiences', function () {
    return Inertia::render('WorkExperiences');
})->name('experiences');
