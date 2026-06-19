<?php

namespace Database\Seeders;

use App\Models\Application;
use App\Models\JobPosting;
use App\Models\StudentProfile;
use Illuminate\Database\Seeder;

class ApplicationSeeder extends Seeder
{
    public function run(): void
    {
        $job = JobPosting::first();
        $student = StudentProfile::first();

        Application::create([
            'job_posting_id' => $job->id,
            'student_profile_id' => $student->id,
            'status' => 'Submitted',
        ]);
    }
}
