<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\Attributes\Fillable;

#[Fillable(['user_id', 'study_program_id', 'nim', 'cv_path'])]
class StudentProfile extends Model
{
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function studyProgram()
    {
        return $this->belongsTo(StudyProgram::class);
    }

    public function applications()
    {
        return $this->hasMany(Application::class);
    }
}
