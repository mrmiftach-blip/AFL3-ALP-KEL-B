<?php

namespace App\Http\Controllers;

use App\Models\Application;
use App\Models\CompanyProfile;
use App\Models\JobPosting;
use App\Models\StudyProgram;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class AdminController extends Controller
{
    /**
     * Dashboard admin: ringkasan statistik + lowongan terbaru.
     */
    public function dashboard()
    {
        $totalProdi = StudyProgram::count();
        $activeJobs = JobPosting::where('deadline_date', '>=', now())->count();
        $totalCompanies = CompanyProfile::count();
        $totalApplicants = Application::count();

        $recentJobs = JobPosting::with('companyProfile')
            ->withCount('applications')
            ->latest()
            ->take(5)
            ->get();

        return view('admin.dashboard', compact(
            'totalProdi',
            'activeJobs',
            'totalCompanies',
            'totalApplicants',
            'recentJobs'
        ));
    }

    /* ===================== Master Data: Program Studi (CRUD) ===================== */

    public function studyPrograms()
    {
        $studyPrograms = StudyProgram::withCount('studentProfiles')
            ->orderBy('name')
            ->paginate(10);

        return view('admin.study-program', compact('studyPrograms'));
    }

    public function storeStudyProgram(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:study_programs,name',
        ], [], ['name' => 'nama program studi']);

        StudyProgram::create($validated);

        return redirect()->route('admin.study-program')
            ->with('success', 'Program studi "' . $validated['name'] . '" berhasil ditambahkan.');
    }

    public function updateStudyProgram(Request $request, StudyProgram $studyProgram)
    {
        $validated = $request->validate([
            'name' => [
                'required', 'string', 'max:255',
                Rule::unique('study_programs', 'name')->ignore($studyProgram->id),
            ],
        ], [], ['name' => 'nama program studi']);

        $studyProgram->update($validated);

        return redirect()->route('admin.study-program')
            ->with('success', 'Program studi berhasil diperbarui.');
    }

    public function destroyStudyProgram(StudyProgram $studyProgram)
    {
        // Pengaman: study_program_id pada student_profiles ber-cascade,
        // jadi menghapus prodi yang masih dipakai akan ikut menghapus mahasiswanya.
        if ($studyProgram->studentProfiles()->exists()) {
            return redirect()->route('admin.study-program')
                ->with('error', 'Program studi "' . $studyProgram->name . '" tidak dapat dihapus karena masih dipakai oleh mahasiswa.');
        }

        $name = $studyProgram->name;
        $studyProgram->delete();

        return redirect()->route('admin.study-program')
            ->with('success', 'Program studi "' . $name . '" berhasil dihapus.');
    }

    /* ===================== Moderasi: Daftar Lowongan ===================== */

    public function jobs(Request $request)
    {
        $search = $request->query('search');

        $jobs = JobPosting::query()
            ->with(['companyProfile', 'studyPrograms'])
            ->withCount('applications')
            ->when($search, function ($query) use ($search) {
                $query->where(function ($q) use ($search) {
                    $q->where('title', 'like', "%{$search}%")
                        ->orWhereHas('companyProfile', function ($c) use ($search) {
                            $c->where('company_name', 'like', "%{$search}%");
                        });
                });
            })
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view('admin.job', compact('jobs', 'search'));
    }

    public function destroyJob(JobPosting $jobPosting)
    {
        // Hard delete cascade: lamaran & relasi pivot prodi ikut terhapus
        // sesuai constraint onDelete('cascade') di migration.
        $title = $jobPosting->title;
        $jobPosting->delete();

        return redirect()->route('admin.job')
            ->with('success', 'Lowongan "' . $title . '" berhasil di-take down.');
    }

    /* ===================== (Placeholder bawaan) ===================== */

    public function companies(Request $request)
    {
        return view('admin.company');
    }
}
