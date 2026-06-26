<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\StudentController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/',[StudentController::class,'dashboard'])->name('student.dashboard');

Route::prefix('student')->group(function(){
    Route::post('/save',[StudentController::class,'store'])->name('student.store');
    Route::get('/list',[StudentController::class,'index'])->name('student.list');
    Route::get('/delete/{id}',[StudentController::class,'delete'])->name('student.delete');
    Route::get('/edit/{id}',[StudentController::class,'edit'])->name('student.edit');
    Route::post('/update',[StudentController::class,'update'])->name('student.update');
    Route::get('/export-pdf',[StudentController::class,'exportPdf'])->name('student.export.pdf');
    // Export all students to an Excel (.xlsx) file
    Route::get('/export-excel',[StudentController::class,'exportExcel'])->name('student.export.excel');
});


