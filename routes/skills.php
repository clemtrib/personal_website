<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SkillController;

// Routes pour les actions CRUD du WorkExperienceController
Route::prefix('skills')->group(function () {
    Route::get('/list', [SkillController::class, 'index'])->name('skills.index');
});


