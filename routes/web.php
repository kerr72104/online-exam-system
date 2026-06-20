<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\LandingController;
use App\Http\Controllers\Student\DashboardController;
use App\Http\Controllers\Student\ExamController;
use Illuminate\Support\Facades\Route;

Route::get('/', [LandingController::class, 'index'])->name('landing');
Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
Route::post('/register', [AuthController::class, 'register'])->name('register');
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

Route::middleware('auth')->group(function () {

    //Admin
    Route::middleware('role:admin')->prefix('admin')->group(function () {
        Route::resource('users', UserController::class);
        Route::resource('subjects', SubjectController::class);
        Route::resource('sections', SectionController::class);
    });

    //Teacher
    Route::middleware('role:teacher')->prefix('teacher')->group(function () {
        Route::resource('questions', QuestionController::class);
        Route::resource('exams', ExamController::class);
        Route::get('results', [ResultController::class, 'index']);
        Route::get('results/{exam}', [ResultController::class, 'show']);
    });

    //Student
    Route::middleware('role:student')->prefix('student')->group(function () {
        Route::get('dashboard', [DashboardController::class, 'index'])->name('student.dashboard');
        Route::get('results', [ExamController::class, 'results'])->name('student.results');
        Route::get('exams/{exam}', [ExamController::class, 'show'])->name('student.exams.show');
        Route::post('exams/{exam}/start', [ExamController::class, 'start'])->name('student.exams.start');
        Route::post('exams/{exam}/submit', [ExamController::class, 'submit'])->name('student.exams.submit');
    });
});