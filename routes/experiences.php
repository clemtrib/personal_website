<?php

use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use App\Http\Controllers\WorkExperienceController;

Route::prefix('dashboard/experiences')->middleware('auth')->group(function () {
    Route::get('/list', [WorkExperienceController::class, 'index'])->name('experiences');
    Route::get('/create', function () { return Inertia::render('WorkExperiencesForm'); })->name('experiences.create');
    Route::post('/create', [WorkExperienceController::class, 'store'])->name('experiences.store');
    Route::get('/{workExperience}/edit', [WorkExperienceController::class, 'edit'])->name('experiences.edit');
    Route::put('/{workExperience}', [WorkExperienceController::class, 'update'])->name('experiences.update');
    Route::delete('/{workExperience}', [WorkExperienceController::class, 'destroy'])->name('experiences.destroy');
});
