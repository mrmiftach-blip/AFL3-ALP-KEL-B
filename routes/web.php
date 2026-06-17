<?php

use App\Enums\UserRoleEnum;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('job.list');
});

Route::controller(\App\Http\Controllers\AuthController::class)->group(function () {
    Route::match(['get', 'post'], '/login', 'login')->middleware('guest')->name('login');
    Route::match(['get', 'post'], '/register', 'register')->middleware('guest')->name('register');
    Route::match(['get', 'post'], '/logout', 'logout')->middleware('auth')->name('logout');
});

Route::controller(\App\Http\Controllers\JobController::class)->group(function () {
    Route::get('/jobs', 'explore')->name('job.list');
    Route::match(['get', 'post'], '/jobs/{id}', 'single')->name('job.single')->middleware('auth');
});

Route::middleware(['auth', 'role:' . UserRoleEnum::Admin->value])->prefix('admin')->name('admin.')->controller(\App\Http\Controllers\AdminController::class)->group(function () {
    Route::get('/dashboard', 'dashboard')->name('dashboard');
    Route::match(['get', 'post'], '/study-programs', 'studyPrograms')->name('study-program');
    Route::match(['get', 'post'], '/companies', 'companies')->name('company');
    Route::match(['get', 'post'], '/jobs', 'jobs')->name('job');
});

Route::middleware(['auth', 'role:' . UserRoleEnum::Company->value])->prefix('company')->name('company.')->controller(\App\Http\Controllers\CompanyController::class)->group(function () {
    Route::get('/dashboard', 'dashboard')->name('dashboard');
    Route::match(['get', 'post'], '/profile', 'profile')->name('profile');
    Route::get('/jobs', 'jobs')->name('job.list');
    Route::match(['get', 'post'], '/jobs/create', 'jobForm')->name('job.create');
    Route::match(['get', 'post'], '/jobs/{id}/edit', 'jobForm')->name('job.edit');
    Route::match(['get', 'post'], '/jobs/{id}/applicants', 'applicants')->name('applicant');
});

Route::middleware(['auth', 'role:' . UserRoleEnum::Student->value])->prefix('student')->name('student.')->controller(\App\Http\Controllers\StudentController::class)->group(function () {
    Route::get('/dashboard', 'dashboard')->name('dashboard');
    Route::match(['get', 'post'], '/profile', 'profile')->name('profile');
    Route::get('/applications', 'applications')->name('application');
});
