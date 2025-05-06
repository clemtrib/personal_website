<?php

use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use App\Http\Controllers\HobbyController;

Route::prefix('dashboard/hobbies')->middleware('auth')->group(function () {
    Route::get('/list', [HobbyController::class, 'index'])->name('hobbies');
    Route::get('/create', function () {
        return Inertia::render('HobbiesForm');
    })->name('hobbies.create');
    Route::post('/create', [HobbyController::class, 'store'])->name('hobbies.store');
    Route::get('/{hobby}/edit', [HobbyController::class, 'edit'])->name('hobbies.edit');
    Route::put('/{hobby}', [HobbyController::class, 'update'])->name('hobbies.update');
    Route::delete('/{hobby}', [HobbyController::class, 'destroy'])->name('hobbies.destroy');
});
