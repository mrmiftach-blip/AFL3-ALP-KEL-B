<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Application;
use Illuminate\Support\Facades\Notification;
use App\Notifications\SelectionResultUpdated;

class CompanyController extends Controller
{
    public function dashboard(Request $request)
    {
        $profile = $request->user()->companyProfile;
        
        // Ambil data lowongan beserta jumlah pelamarnya
        $jobPostings = $profile ? $profile->jobPostings()->withCount('applications')->latest()->get() : collect();
        
        // Hitung statistik untuk Dashboard
        $totalJobs = $jobPostings->count();
        $totalApplicants = $jobPostings->sum('applications_count');

        return view('company.dashboard', compact('jobPostings', 'totalJobs', 'totalApplicants'));
    }

    public function profile(Request $request)
    {
        $profile = $request->user()->companyProfile;

        if ($request->isMethod('post')) {
            $validated = $request->validate([
                'company_name' => 'required|string|max:255',
                'description' => 'nullable|string',
                'address' => 'nullable|string',
            ]);

            if ($profile) {
                $profile->update($validated);
            } else {
                $request->user()->companyProfile()->create($validated);
            }

            return redirect()->back()->with('success', 'Profil perusahaan berhasil diperbarui!');
        }

        return view('company.profile', compact('profile'));
    }


    public function jobForm(Request $request, $id = null)
    {
        if ($request->isMethod('post')) {
            // TODO: Implementasikan logika pembuatan/pembaruan lowongan
        }
        return view('company.job-form');
    }

    public function applicants(Request $request, $jobId)
    {
        // Pastikan lowongan ini memang milik perusahaan yang sedang login
        $jobPosting = $request->user()->companyProfile->jobPostings()->findOrFail($jobId);

        if ($request->isMethod('post')) {
            $request->validate([
                'application_id' => 'required|exists:applications,id',
                'status' => 'required|in:Reviewed,Accepted,Rejected'
            ]);

            // Pastikan lamaran ini benar-benar untuk lowongan ini
            $application = $jobPosting->applications()->findOrFail($request->application_id);

            // Check if status is actually changing to avoid duplicate notifications
            if ($application->status->value !== $request->status) {
                $application->status = $request->status;
                $application->save();

                // Trigger Notifikasi
                $studentUser = $application->studentProfile->user;
                if ($studentUser) {
                    Notification::send($studentUser, new SelectionResultUpdated($application));
                }
            }

            return redirect()->back()->with('success', 'Status lamaran berhasil diperbarui menjadi ' . $request->status);
        }

        // Ambil semua pelamar untuk lowongan ini
        $applications = $jobPosting->applications()->with('studentProfile.user')->get();

        return view('company.applicant', compact('jobPosting', 'applications'));
    }
}
