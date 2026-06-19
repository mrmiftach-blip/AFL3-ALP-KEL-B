<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Illuminate\Validation\Rules\File;
use Illuminate\Support\Facades\Storage;
use App\Models\StudentProfile;
use App\Enums\UserRoleEnum;

class StudentController extends Controller
{
    function dashboard()
    {
        return view('student.dashboard');
    }

    function profile(Request $request)
    {
        // Mengambil relasi profil mahasiswa dari user yang sedang login
        $profile = $request->user()->studentProfile;

        // Menangkap POST saat user menyimpan form profil/CV
        if ($request->isMethod('post')) {
            // Validasi input file cv agar hanya menerima PDF dengan ukuran maksimal 2MB
            $request->validate([
                'cv' => ['nullable', File::types(['pdf'])->max('2mb')]
            ]);

            // Jika user mengunggah file CV baru
            if ($request->hasFile('cv')) {
                // Hapus file CV lama (jika ada) dari penyimpanan lokal agar storage tidak penuh
                if ($profile && $profile->cv_path) {
                    Storage::disk('local')->delete($profile->cv_path);
                }

                // Simpan file baru ke folder 'resumes' di dalam disk 'local' (tidak bisa diakses publik)
                $path = $request->file('cv')->store('resumes', 'local');

                // Jika user belum punya profil sama sekali, buat data profil baru dengan nilai default
                if (!$profile) {
                    $profile = StudentProfile::create([
                        'user_id' => $request->user()->id,
                        'nim' => $request->nim ?? 'TBD',
                        'study_program_id' => $request->study_program_id ?? 1,
                        'cv_path' => $path
                    ]);
                } else {
                    // Jika profil sudah ada, cukup perbarui kolom cv_path saja
                    $profile->cv_path = $path;
                    $profile->save();
                }
            }

            // Kembalikan ke halaman sebelumnya dengan membawa pesan sukses
            return redirect()->back()->withSuccess('Profil dan CV berhasil diperbarui.');
        }

        // jangan hapus, untuk development
        // dd($profile);
        // return $profile;

        // Menampilkan halaman profil dan mengirimkan data profil yang aktif
        return view('student.profile', [
            'profile' => $profile
        ]);
    }

    function downloadCv(string $id, Request $request)
    {
        // Cari data profil berdasarkan ID, tampilkan error 404 jika tidak ditemukan
        $profile = StudentProfile::findOrFail($id);

        // Pastikan file tersebut benar-benar tersimpan dan rekam jejak path-nya ada
        if (!$profile->cv_path || !Storage::disk('local')->exists($profile->cv_path)) {
            abort(404, 'CV tidak ditemukan.');
        }

        $user = $request->user();

        // Variabel untuk mengecek apakah user adalah pemilik CV atau seorang Admin
        $isOwner = $user->id === $profile->user_id;
        $isAdmin = $user->role === UserRoleEnum::Admin->value;

        // Cek apakah user adalah Perusahaan yang sedang dilamar oleh mahasiswa tersebut
        $isCompanyWithApplication = false;
        if ($user->role === UserRoleEnum::Company->value && $user->companyProfile) {
            $isCompanyWithApplication = \App\Models\Application::whereHas('jobPosting', function ($q) use ($user) {
                $q->where('company_profile_id', $user->companyProfile->id);
            })->where('student_profile_id', $profile->id)->exists();
        }

        // Tolak akses jika bukan pemilik, bukan admin, dan bukan perusahaan yang dilamar
        if (!$isOwner && !$isAdmin && !$isCompanyWithApplication) {
            abort(403, 'Anda tidak memiliki akses untuk mengunduh CV ini.');
        }

        // Berikan respon file untuk diunduh langsung dari penyimpanan lokal yang aman
        return Storage::disk('local')->download($profile->cv_path, 'CV_' . $profile->nim . '.pdf');
    }

    function applications(Request $request)
    {
        return view('student.application');
    }
}
