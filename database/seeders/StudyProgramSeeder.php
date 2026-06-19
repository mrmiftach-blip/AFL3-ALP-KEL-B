<?php

namespace Database\Seeders;

use App\Models\StudyProgram;
use Illuminate\Database\Seeder;

class StudyProgramSeeder extends Seeder
{
    public function run(): void
    {
        StudyProgram::create(['name' => 'Teknik Informatika']);
        StudyProgram::create(['name' => 'Sistem Informasi']);
    }
}
