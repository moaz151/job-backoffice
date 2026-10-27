<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\JobApplicationController;
use App\Http\Controllers\CompanyController;
use App\Http\Controllers\JobCategoryController;
use App\Http\Controllers\JobVacancyController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

// shared Routes
Route::middleware(['auth', 'role:admin,company-owner'])->group(function () {
    Route::get('/', [DashboardController::class,'index'])->name('dashboard');
    
    // Job Applications
    Route::resource('job-applications', JobApplicationController::class);
    Route::get('job-applications/{id}/restore', [JobApplicationController::class, 'restore'])->name('job-applications.restore');
    // Job Vacancies
    Route::resource('job-vacancies', JobVacancyController::class);
    Route::get('job-vacancies/{id}/restore', [JobVacancyController::class, 'restore'])->name('job-vacancies.restore');
});

// Company Routes
Route::middleware(['auth', 'role:company-owner'])->group(function () {
    Route::get('/my-company', [CompanyController::class,'show'])->name('my-company.show');
    Route::get('/my-company/edit', [CompanyController::class,'edit'])->name('my-company.edit');
    Route::put('/my-company', [CompanyController::class,'update'])->name('my-company.update');
});

// Admin Routes
Route::middleware(['auth', 'role:admin'])->group(function () {
    // Companies
    Route::resource('companies', CompanyController::class);
    Route::get('companies/{id}/restore', [CompanyController::class, 'restore'])->name('companies.restore');

    // Users
    Route::resource('users', UserController::class);
    Route::get('users/{id}/restore', [UserController::class, 'restore'])->name('users.restore');

    // Categories
    Route::resource('job-categories', JobCategoryController::class);
    Route::get('job-categories/{id}/restore', [JobCategoryController::class, 'restore'])->name('job-categories.restore');
});

require __DIR__.'/auth.php';
