<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function dashboard()
    {
        return view('admin.dashboard');
    }

    public function studyPrograms(Request $request)
    {
        if ($request->isMethod('post')) {
            // TODO: Implementasikan logika pembuatan/pembaruan/penghapusan (CRUD)
        }
        return view('admin.study-program');
    }

    public function companies(Request $request)
    {
        if ($request->isMethod('post')) {
            // TODO: Implementasikan logika pengelolaan/penghapusan perusahaan
        }
        return view('admin.company');
    }

    public function jobs(Request $request)
    {
        if ($request->isMethod('post')) {
            // TODO: Implementasikan logika takedown/hapus lowongan
        }
        return view('admin.job');
    }
}
