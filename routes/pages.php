<?php

use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use App\Http\Controllers\PageController;

Route::prefix('api/pages')->group(function () {
    Route::get('/list', [PageController::class, 'index'])->name('pages.index');
});

Route::prefix('dashboard/pages')->group(function () {
    Route::get('/list', function () { return Inertia::render('Pages'); })->name('pages');
    Route::get('/create', function () { return Inertia::render('PagesForm'); })->name('pages.create');
    Route::post('/create', [PageController::class, 'store'])->name('pages.store');
    Route::get('/{page}/edit', [PageController::class, 'edit'])->name('pages.edit');
    Route::put('/{page}', [PageController::class, 'update'])->name('pages.update');
    Route::delete('/{page}', [PageController::class, 'destroy'])->name('pages.destroy');
});
