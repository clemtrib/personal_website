<?php

use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use App\Http\Controllers\SkillController;

Route::prefix('dashboard/skills')->middleware('auth')->group(function () {
    Route::get('/list', [SkillController::class, 'index'])->name('skills');
    Route::get('/create', function () {
        return Inertia::render('SkillsForm');
    })->name('skills.create');
    Route::post('/create', [SkillController::class, 'store'])->name('skills.store');
    Route::get('/{skill}/edit', [SkillController::class, 'edit'])->name('skills.edit');
    Route::put('/{skill}', [SkillController::class, 'update'])->name('skills.update');
    Route::delete('/{skill}', [SkillController::class, 'destroy'])->name('skills.destroy');
    Route::post('/reorder', [SkillController::class, 'reorder'])->name('skills.reorder');
});
