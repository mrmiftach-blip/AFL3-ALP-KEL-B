<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\StudyProgram;
use App\Models\CompanyProfile;
use App\Models\StudentProfile;
use App\Enums\UserRoleEnum;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Menjalankan database seeds.
     */
    public function run(): void
    {
        $password = Hash::make('qwerty123');

        // Admin
        User::create([
            'name' => 'admin',
            'email' => 'admin@mail.com',
            'password' => $password,
            'role' => UserRoleEnum::Admin->value,
        ]);

        // Perusahaan
        $company = User::create([
            'name' => 'company1',
            'email' => 'company1@mail.com',
            'password' => $password,
            'role' => UserRoleEnum::Company->value,
        ]);

        $companyProfile = CompanyProfile::create([
            'user_id' => $company->id,
            'company_name' => 'PT Company Satu',
            'description' => 'Perusahaan IT terkemuka.',
            'address' => 'Jl. Teknologi No. 1',
        ]);

        // Mahasiswa
        $student = User::create([
            'name' => 'student satu',
            'email' => 'student1@mail.com',
            'password' => $password,
            'role' => UserRoleEnum::Student->value,
        ]);

        $studyProgram = StudyProgram::firstOrCreate(['name' => 'Teknik Informatika']);

        $studentProfile = StudentProfile::create([
            'user_id' => $student->id,
            'study_program_id' => $studyProgram->id,
            'nim' => '070601231001',
        ]);
    }
}
