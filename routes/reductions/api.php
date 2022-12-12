<?php

use App\Http\Controllers\ReductionsController;
use Illuminate\Support\Facades\Route;

Route::prefix('reductions')->middleware('auth:sanctum', 'auth:supervisors')->group(function () {

    #create new reduction
    Route::post('/', [ReductionsController::class, 'store'])->name('reductions.store');


    #get all reductions
    Route::get('/', [ReductionsController::class, 'index'])->name('reductions.index');

    #get one reduction
    Route::get('/{id}', [ReductionsController::class, 'show'])->name('reductions.show');

    #update reduction
    Route::put('/{id}', [ReductionsController::class, 'update'])->name('reductions.update');

    #remove reduction
    Route::delete('/{id}', [ReductionsController::class, 'destroy'])->name('reductions.remove');
});
