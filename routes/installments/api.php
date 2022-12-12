<?php

use App\Http\Controllers\InstallmentsController;
use Illuminate\Support\Facades\Route;

Route::prefix('installments')->middleware('auth:sanctum', 'auth:supervisors')->group(function () {

    #create new installment
    Route::post('/', [InstallmentsController::class, 'store'])->name('installments.store');


    #get all installments
    Route::get('/', [InstallmentsController::class, 'index'])->name('installments.index');

    #get one installment
    Route::get('/{id}', [InstallmentsController::class, 'show'])->name('installments.show');

    #update installment
    Route::put('/{id}', [InstallmentsController::class, 'update'])->name('installments.update');

    #remove installment
    Route::delete('/{id}', [InstallmentsController::class, 'destroy'])->name('installments.remove');
});
