<?php

use App\Enums\UserRoleEnum;
use Illuminate\Support\Facades\Route;

// Redirect halaman utama langsung ke daftar lowongan
Route::get('/', function () {
    return redirect()->route('job.list');
});

// Rute Publik & Autentikasi (Tugas Bagian 4: Public Pages & Authentication)
Route::controller(\App\Http\Controllers\AuthController::class)->group(function () {
    Route::match(['get', 'post'], '/login', 'login')->middleware('guest')->name('login');
    Route::match(['get', 'post'], '/register', 'register')->middleware('guest')->name('register');
    Route::match(['get', 'post'], '/logout', 'logout')->middleware('auth')->name('logout');
});

Route::controller(\App\Http\Controllers\JobController::class)->group(function () {
    // Lowongan (Bisa diakses publik tanpa login)
    Route::get('/jobs', 'list')->name('job.list');
    // Detail Lowongan (Bisa diakses publik tanpa login)
    Route::get('/jobs/{id}', 'single')->name('job.single');
    // Apply Lowongan (Wajib login untuk mengirim form POST)
    Route::post('/jobs/{id}', 'single')->name('job.apply')->middleware('auth');
});

// Rute Ekstra  (Koordinator Teknis)
Route::middleware(['auth'])->group(function () {
    // Rute untuk mengunduh CV secara aman (memeriksa otorisasi di dalam controller)
    Route::get('/download-cv/{id}', [\App\Http\Controllers\StudentController::class, 'downloadCv'])->name('student.download-cv');

    // Rute Notifikasi In-App
    Route::get('/notifications', [\App\Http\Controllers\NotificationController::class, 'list'])->name('notification.list');
    Route::get('/notifications/{id}/read', [\App\Http\Controllers\NotificationController::class, 'read'])->name('notification.read');
});

// Rute Panel Admin (Tugas Bagian 5 Panel Admin )
Route::middleware(['auth', 'role:' . UserRoleEnum::Admin->value])->prefix('admin')->name('admin.')->controller(\App\Http\Controllers\AdminController::class)->group(function () {
    Route::get('/dashboard', 'dashboard')->name('dashboard');
    Route::match(['get', 'post'], '/study-programs', 'studyPrograms')->name('study-program');
    Route::match(['get', 'post'], '/companies', 'companies')->name('company');
    Route::match(['get', 'post'], '/jobs', 'jobs')->name('job');
});

// Rute Panel Perusahaan (Tugas Bagian 2: Panel Perusahaan)
Route::middleware(['auth', 'role:' . UserRoleEnum::Company->value])->prefix('company')->name('company.')->controller(\App\Http\Controllers\CompanyController::class)->group(function () {
    Route::get('/dashboard', 'dashboard')->name('dashboard');
    Route::match(['get', 'post'], '/profile', 'profile')->name('profile');
    Route::get('/jobs', 'jobs')->name('job.list');
    Route::match(['get', 'post'], '/jobs/create', 'jobForm')->name('job.create');
    Route::match(['get', 'post'], '/jobs/{id}/edit', 'jobForm')->name('job.edit');
    Route::match(['get', 'post'], '/jobs/{id}/applicants', 'applicants')->name('applicant');
});

// Rute Panel Pelamar/Mahasiswa (Tugas Bagian 3: Panel Pelamar/Mahasiswa)
Route::middleware(['auth', 'role:' . UserRoleEnum::Student->value])->prefix('student')->name('student.')->controller(\App\Http\Controllers\StudentController::class)->group(function () {
    Route::get('/dashboard', 'dashboard')->name('dashboard');
    Route::match(['get', 'post'], '/profile', 'profile')->name('profile');
    Route::get('/applications', 'applications')->name('application');
});



// JANGAN DIHAPUS DULU SEBELUM AUTH SELESAI, buat test
Route::get('/masuk-admin', function () {
    Auth::login(\App\Models\User::where('email', 'admin@mail.com')->first());
    return "Berhasil Login sebagai Admin!";
});
Route::get('/masuk-student', function () {
    Auth::login(\App\Models\User::where('email', 'student1@mail.com')->first());
    return "Berhasil Login sebagai Student1!";
});
Route::get('/masuk-perusahaan', function () {
    Auth::login(\App\Models\User::where('email', 'company1@mail.com')->first());
    return "Berhasil Login sebagai Perusahaan (PT Company Satu)!";
});
Route::get('/keluar', function () {
    Auth::logout();
    request()->session()->invalidate();
    request()->session()->regenerateToken();
    return "Berhasil Logout! Anda sekarang adalah Guest (Tamu).";
});
