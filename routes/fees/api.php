<?php

use App\Http\Controllers\FeesController;
use Illuminate\Support\Facades\Route;

Route::prefix('fees')->middleware('auth:sanctum', 'auth:supervisors')->group(function () {

    #create new fee
    Route::post('/', [FeesController::class, 'store'])->name('fees.store');


    #get all fees
    Route::get('/', [FeesController::class, 'index'])->name('fees.index');

    #get one fee
    Route::get('/{id}', [FeesController::class, 'show'])->name('fees.show');

    #update fee
    Route::put('/{id}', [FeesController::class, 'update'])->name('fees.update');

    #remove fee
    Route::delete('/{id}', [FeesController::class, 'destroy'])->name('fees.remove');
});
