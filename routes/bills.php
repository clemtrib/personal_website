<?php

use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use App\Http\Controllers\CustomerController;

/*
Route::prefix('dashboard/customers')->middleware('auth')->group(function () {
    Route::get('/list', [CustomerController::class, 'index'])->name('customers');
    Route::get('/create', function () {
        return Inertia::render('CustomersForm');
    })->name('customers.create');
    Route::post('/create', [CustomerController::class, 'store'])->name('customers.store');
    Route::get('/{hobby}/edit', [CustomerController::class, 'edit'])->name('customers.edit');
    Route::put('/{hobby}', [CustomerController::class, 'update'])->name('customers.update');
    Route::delete('/{hobby}', [CustomerController::class, 'destroy'])->name('customers.destroy');
    Route::post('/reorder', [CustomerController::class, 'reorder'])->name('customers.reorder');
});
*/
