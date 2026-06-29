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

    // Master Data: Program Studi (CRUD)
    Route::get('/study-programs', 'studyPrograms')->name('study-program');
    Route::post('/study-programs', 'storeStudyProgram')->name('study-program.store');
    Route::put('/study-programs/{studyProgram}', 'updateStudyProgram')->name('study-program.update');
    Route::delete('/study-programs/{studyProgram}', 'destroyStudyProgram')->name('study-program.destroy');

    // Moderasi: Daftar Lowongan
    Route::get('/jobs', 'jobs')->name('job');
    Route::delete('/jobs/{jobPosting}', 'destroyJob')->name('job.destroy');

    // Placeholder bawaan
    Route::get('/companies', 'companies')->name('company');
});

// Rute Panel Perusahaan (Tugas Bagian 2: Panel Perusahaan)
Route::middleware(['auth', 'role:' . UserRoleEnum::Company->value])->prefix('company')->name('company.')->controller(\App\Http\Controllers\CompanyController::class)->group(function () {
    Route::get('/', function () {
        return redirect()->route('company.dashboard');
    });
    Route::get('/dashboard', 'dashboard')->name('dashboard');
    Route::match(['get', 'post'], '/profile', 'profile')->name('profile');
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
    return "<h3 style='font-family: sans-serif;'>Berhasil Login sebagai Admin!</h3>
            <a href='/admin/dashboard'><button style='padding: 10px 20px; cursor: pointer; font-size: 16px;'>Lanjut ke Panel Admin</button></a>";
});
Route::get('/masuk-student', function () {
    Auth::login(\App\Models\User::where('email', 'student1@mail.com')->first());
    return "<h3 style='font-family: sans-serif;'>Berhasil Login sebagai Student1!</h3>
            <a href='/jobs'><button style='padding: 10px 20px; cursor: pointer; font-size: 16px;'>Lanjut ke Daftar Lowongan (/jobs)</button></a>";
});
Route::get('/masuk-perusahaan', function () {
    Auth::login(\App\Models\User::where('email', 'company1@mail.com')->first());
    return "<h3 style='font-family: sans-serif;'>Berhasil Login sebagai Perusahaan (PT Company Satu)!</h3>
            <a href='/company/dashboard'><button style='padding: 10px 20px; cursor: pointer; font-size: 16px;'>Lanjut ke Panel Perusahaan</button></a>";
});
Route::get('/keluar', function () {
    Auth::logout();
    request()->session()->invalidate();
    request()->session()->regenerateToken();
    return "Berhasil Logout! Anda sekarang adalah Guest (Tamu).";
});
