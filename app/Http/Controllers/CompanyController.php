<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

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
        }
        return view('company.applicant');
    }
}
