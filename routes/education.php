<?php

use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use App\Http\Controllers\EducationController;

Route::prefix('dashboard/education')->middleware('auth')->group(function () {
    Route::get('/list', [EducationController::class, 'index'])->name('education');
    Route::get('/create', function () { return Inertia::render('EducationForm'); })->name('education.create');
    Route::post('/create', [EducationController::class, 'store'])->name('education.store');
    Route::get('/{education}/edit', [EducationController::class, 'edit'])->name('education.edit');
    Route::put('/{education}', [EducationController::class, 'update'])->name('education.update');
    Route::delete('/{education}', [EducationController::class, 'destroy'])->name('education.destroy');
});
