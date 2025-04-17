<?php

use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use App\Http\Controllers\WorkExperienceController;

// Routes pour les actions CRUD du WorkExperienceController
Route::prefix('experiences')->group(function () {
    Route::get('/list', [WorkExperienceController::class, 'index'])->name('experiences.index');
    Route::get('/{workExperience}', [WorkExperienceController::class, 'show'])->name('experiences.show');
    Route::put('/{workExperience}', [WorkExperienceController::class, 'update'])->name('experiences.update');
    Route::delete('/{workExperience}', [WorkExperienceController::class, 'destroy'])->name('experiences.destroy');
});

// Route pour afficher la liste de messages
Route::get('/dashboard/experiences', function () {
    return Inertia::render('WorkExperiences');
})->name('experiences');

// Route pour afficher la liste de messages
Route::get('/dashboard/experiences/create', function () {
    return Inertia::render('WorkExperiencesForm');
})->name('experiences.create');

Route::post('/dashboard/experiences/create', [WorkExperienceController::class, 'store'])->name('experiences.store');
