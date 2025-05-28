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
    Route::get('/is_paid/{bill}', [BillController::class, 'isPaid'])->name('bills.isPaid');
    Route::get('/send_by_mail/{bill}', [BillController::class, 'sendByMail'])->name('bills.sendByMail');
    Route::delete('/{bill}', [BillController::class, 'destroy'])->name('bills.destroy');
});
