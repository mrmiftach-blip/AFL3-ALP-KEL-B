<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Application;
use Illuminate\Support\Facades\Notification;
use App\Notifications\SelectionResultUpdated;

class CompanyController extends Controller
{
    public function dashboard()
    {
        return view('company.dashboard');
    }

    public function profile(Request $request)
    {
        if ($request->isMethod('post')) {
            // TODO: Implementasikan logika pembaruan profil
        }
        return view('company.profile');
    }

    public function jobs(Request $request)
    {
        return view('company.job-list');
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
        if ($request->isMethod('post')) {
            // TODO: Implementasikan logika pembaruan status pelamar
            // maaf, ini aku kerjakan sebagian buat ngetest notification ya.
            $request->validate([
                'application_id' => 'required|exists:applications,id',
                'status' => 'required|in:Accepted,Rejected'
            ]);

            $application = Application::where('job_posting_id', $jobId)
                ->where('id', $request->application_id)
                ->firstOrFail();

            // Check if status is actually changing to avoid duplicate notifications
            if ($application->status !== $request->status) {
                $application->status = $request->status;
                $application->save();

                // Trigger Notifikasi
                $studentUser = $application->studentProfile->user;
                if ($studentUser) {
                    Notification::send($studentUser, new SelectionResultUpdated($application));
                }
            }

            return redirect()->back()->with('success', 'Status lamaran berhasil diperbarui!');
        }
        return view('company.applicant');
    }
}
