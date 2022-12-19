<?php

use App\Http\Controllers\SupervisorActivityController;
use Illuminate\Support\Facades\Route;

Route::prefix('/logs/activities')->middleware('auth:sanctum', 'auth:supervisors')->group(function () {
    #get all activities
    Route::get('/', [SupervisorActivityController::class, 'index'])->name('supervisors.activities.index');
});
