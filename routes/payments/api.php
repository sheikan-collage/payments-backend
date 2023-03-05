<?php

use App\Http\Controllers\PaymentsController;
use App\Http\Middleware\AuthBank;
use Illuminate\Support\Facades\Route;


Route::prefix('payments')->middleware('auth:sanctum', 'auth:supervisors')->group(function () {
    #get all payments
    Route::get('/', [PaymentsController::class, 'index'])->name('payments.index');
});

Route::prefix('banks')->middleware(AuthBank::class)->group(function () {

    #get students data
    Route::get('/{bank_id}/students/{university_id}', [PaymentsController::class, 'getStudent'])->name('payments.getStudentsData');

    #get students data
    Route::post('/{bank_id}/students/{university_id}/payments', [PaymentsController::class, 'pay'])->name('payments.pay');
});
