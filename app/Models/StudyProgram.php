<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\Attributes\Fillable;

#[Fillable(['name'])]
class StudyProgram extends Model
{
    public function studentProfiles()
    {
        return $this->hasMany(StudentProfile::class);
    }

    public function jobPostings()
    {
        return $this->hasMany(JobPosting::class);
    }
}
