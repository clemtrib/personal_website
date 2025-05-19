<?php

use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use App\Http\Controllers\GoogleMeetController;

// Route pour afficher la liste de messages
//Route::post('meet', [GoogleMeetController::class, 'book'])->name('meets.book');
Route::put('meet/{timeslot}', [GoogleMeetController::class, 'book'])->name('meets.book');

Route::prefix('dashboard/meets')->middleware('auth')->group(function () {
    Route::get('/list', [GoogleMeetController::class, 'index'])->name('meets');
    Route::get('/create', function () { return Inertia::render('MeetsForm'); })->name('meets.create');
    Route::post('/create', [GoogleMeetController::class, 'store'])->name('meets.store');
    Route::get('/{timeslot}/edit', [GoogleMeetController::class, 'edit'])->name('meets.edit');
    Route::put('/{timeslot}', [GoogleMeetController::class, 'update'])->name('meets.update');
    Route::delete('/{timeslot}', [GoogleMeetController::class, 'destroy'])->name('meets.destroy');
});
