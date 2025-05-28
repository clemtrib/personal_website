<?php

use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use App\Http\Controllers\BillController;

Route::prefix('dashboard/bills')->middleware('auth')->group(function () {
    Route::get('/list', [BillController::class, 'index'])->name('bills');
    Route::get('/create', [BillController::class, 'create'])->name('bills.create');
    Route::post('/create', [BillController::class, 'store'])->name('bills.store');
    Route::get('/{bill}/edit', [BillController::class, 'edit'])->name('bills.edit');
    Route::get('/download/{bill}', [BillController::class, 'downloadInvoice'])->name('bills.download');
    Route::put('/is_cancelled/{bill}', [BillController::class, 'update'])->name('bills.is_cancelled');
    Route::put('/is_paid/{bill}', [BillController::class, 'update'])->name('bills.is_paid');
    Route::delete('/{bill}', [BillController::class, 'destroy'])->name('bills.destroy');
});
