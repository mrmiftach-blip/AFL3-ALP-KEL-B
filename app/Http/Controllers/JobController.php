<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\JobPosting;
use App\Models\StudyProgram;

class JobController extends Controller
{
    function list(Request $request)
    {
        // Start query untuk model JobPosting beserta relasi companyProfile dan studyProgram
        $query = JobPosting::query()->with(['companyProfile', 'studyProgram']);

        // Auto-Close: Hanya tampilkan lowongan yang tanggal penutupannya hari ini atau di masa depan
        $query->whereDate('deadline_date', '>=', now());

        // Jika ada input 'search', tambahkan kondisi pencarian pada judul atau deskripsi
        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%");
            });
        }

        // Jika ada filter 'study_program', cari lowongan dengan ID program studi tersebut
        if ($request->has('study_program') && $request->study_program != '') {
            $query->where('study_program_id', $request->study_program);
        }

        // Ambil data terbaru dengan paginasi 10 per halaman, dan pertahankan parameter URL (query string)
        $jobs = $query->latest()->paginate(10)->withQueryString();

        // Ambil semua data program studi untuk opsi filter di halaman eksplorasi
        $studyPrograms = StudyProgram::all();

        // jangan hapus, untuk development
        // dd($jobs);
        // return $jobs;

        // Kembalikan ke halaman daftar lowongan dengan membawa data
        return view('job.list', [
            'jobs' => $jobs,
            'studyPrograms' => $studyPrograms
        ]);
    }

    function single(string $id, Request $request)
    {
        // Cari data lowongan berdasarkan ID beserta relasinya, atau akan error 404 jika tidak ketemu
        $job = JobPosting::with(['companyProfile.user', 'studyProgram'])->findOrFail($id);

        // Tandai true jika tanggal deadline sudah terlewat
        $isClosed = $job->deadline_date < now();

        // Menangkap aksi POST (biasanya saat tombol Apply ditekan)
        if ($request->isMethod('post')) {
            // Mencegah pelamar melamar lowongan yang sudah tutup
            if ($isClosed) {
                return back()->withErrors(['alert' => 'Lowongan ini sudah ditutup.']);
            }
            // TODO: Implementasikan logika 1-Click Apply
            return redirect()->back();
        }

        // jangan hapus, untuk development
        // dd($job);
        // return $job;

        // Menampilkan detail lowongan dan membawa variabel penanda status tutup
        return view('job.single', [
            'job' => $job,
            'isClosed' => $isClosed
        ]);
    }
}
