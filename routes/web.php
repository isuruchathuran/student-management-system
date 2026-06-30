<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\SubjectController;
use App\Http\Controllers\TeacherController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// ── Root redirect → dashboard ────────────────────────────────────────────
Route::redirect('/', '/dashboard');

// ── Dashboard ────────────────────────────────────────────────────────────
Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

// ── Students ─────────────────────────────────────────────────────────────
Route::prefix('students')->name('students.')->group(function () {
    Route::get('/',            [StudentController::class, 'index'])->name('index');
    Route::post('/save',       [StudentController::class, 'store'])->name('store');
    Route::get('/delete/{id}', [StudentController::class, 'delete'])->name('delete');
    Route::get('/edit/{id}',   [StudentController::class, 'edit'])->name('edit');
    Route::post('/update',     [StudentController::class, 'update'])->name('update');

    // Export / Import
    Route::get('/export-pdf',   [StudentController::class, 'exportPdf'])->name('export.pdf');
    Route::get('/export-excel', [StudentController::class, 'exportExcel'])->name('export.excel');
    Route::post('/import',      [StudentController::class, 'importExcel'])->name('import');

    // JSON endpoint
    Route::get('/next-reg-no', [StudentController::class, 'nextRegNo'])->name('next-reg-no');
});

// Legacy student routes (redirect to new paths for backward compatibility)
Route::redirect('/student/list', '/students');

// ── Teachers ──────────────────────────────────────────────────────────────
Route::prefix('teachers')->name('teachers.')->group(function () {
    Route::get('/',              [TeacherController::class, 'index'])->name('index');
    Route::post('/save',         [TeacherController::class, 'store'])->name('store');
    Route::get('/delete/{id}',   [TeacherController::class, 'delete'])->name('delete');
    Route::post('/update',       [TeacherController::class, 'update'])->name('update');
    Route::get('/next-teacher-id', [TeacherController::class, 'nextTeacherId'])->name('next-teacher-id');
});

// ── Subjects ──────────────────────────────────────────────────────────────
Route::prefix('subjects')->name('subjects.')->group(function () {
    Route::get('/',            [SubjectController::class, 'index'])->name('index');
    Route::post('/save',       [SubjectController::class, 'store'])->name('store');
    Route::get('/delete/{id}', [SubjectController::class, 'delete'])->name('delete');
    Route::post('/update',     [SubjectController::class, 'update'])->name('update');
});

// ── Reports ───────────────────────────────────────────────────────────────
Route::get('/reports', [ReportController::class, 'index'])->name('reports.index');

// ── Settings ──────────────────────────────────────────────────────────────
Route::get('/settings', function () {
    return view('settings.index');
})->name('settings.index');
