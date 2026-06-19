<?php

namespace Database\Seeders;

use App\Models\JobPosting;
use App\Models\CompanyProfile;
use App\Models\StudyProgram;
use Illuminate\Database\Seeder;

class JobPostingSeeder extends Seeder
{
    public function run(): void
    {
        $companyProfile = CompanyProfile::first();
        $ti = StudyProgram::where('name', 'Teknik Informatika')->first();
        $si = StudyProgram::where('name', 'Sistem Informasi')->first();

        JobPosting::create([
            'company_profile_id' => $companyProfile->id,
            'study_program_id' => $ti->id,
            'title' => 'Software Engineer / Programmer Intern',
            'description' => 'Membangun aplikasi web yang menakjubkan menggunakan framework Laravel dan React.',
            'deadline_date' => now()->addDays(30),
        ]);

        JobPosting::create([
            'company_profile_id' => $companyProfile->id,
            'study_program_id' => $si->id,
            'title' => 'System Analyst Intern',
            'description' => 'Menganalisis sistem informasi dan membuat dokumen spesifikasi kebutuhan.',
            'deadline_date' => now()->subDays(2), // tes lowongan yang sudah tutup (Auto-Close)
        ]);

        JobPosting::create([
            'company_profile_id' => $companyProfile->id,
            'study_program_id' => $ti->id,
            'title' => 'Data Scientist Intern',
            'description' => 'Mengolah data menggunakan Python dan Machine Learning.',
            'deadline_date' => now()->addDays(15),
        ]);
    }
}
