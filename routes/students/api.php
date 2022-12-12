<?php

use App\Http\Controllers\StudentsController;
use Illuminate\Support\Facades\Route;

Route::prefix('students')->middleware('auth:sanctum', 'auth:supervisors')->group(function () {

    #create new student
    Route::post('/', [StudentsController::class, 'store'])->name('students.store');


    #get all students
    Route::get('/', [StudentsController::class, 'index'])->name('students.index');

    #get one student
    Route::get('/{id}', [StudentsController::class, 'show'])->name('students.show');

    #update student
    Route::put('/{id}', [StudentsController::class, 'update'])->name('students.update');

    #remove student
    Route::delete('/{id}', [StudentsController::class, 'destroy'])->name('students.remove');
});
