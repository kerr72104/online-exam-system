<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\LandingController;
use Illuminate\Support\Facades\Route;

// Public
Route::get('/', [LandingController::class, 'index'])->name('landing');
Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
Route::post('/register', [AuthController::class, 'register'])->name('register');
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Authenticated
Route::middleware('auth')->group(function () {

    // Admin
    Route::middleware('role:admin')->prefix('admin')->group(function () {
        Route::resource('users', Admin\UserController::class);
        Route::resource('subjects', Admin\SubjectController::class);
        Route::resource('sections', Admin\SectionController::class);
    });

    // Teacher
    Route::middleware('role:teacher')->prefix('teacher')->group(function () {
        Route::resource('questions', Teacher\QuestionController::class);
        Route::resource('exams', Teacher\ExamController::class);
        Route::get('results', [Teacher\ResultController::class, 'index']);
        Route::get('results/{exam}', [Teacher\ResultController::class, 'show']);
    });

    // Student
    Route::middleware('role:student')->prefix('student')->group(function () {
        Route::get('dashboard', [Student\DashboardController::class, 'index']);
        Route::get('exams/{exam}', [Student\ExamController::class, 'show']);
        Route::post('exams/{exam}/start', [Student\ExamController::class, 'start']);
        Route::post('exams/{exam}/submit', [Student\ExamController::class, 'submit']);
    });
});