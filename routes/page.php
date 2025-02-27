<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PageController;

// Routes pour les actions CRUD du PageController
Route::prefix('page')->group(function () {
    Route::get('/page/{n}', [PageController::class, 'show']);
});



