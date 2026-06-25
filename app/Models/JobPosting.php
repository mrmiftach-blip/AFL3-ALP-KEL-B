<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\Attributes\Fillable;

#[Fillable(['company_profile_id', 'title', 'description', 'deadline_date'])]
class JobPosting extends Model
{
    protected function casts(): array
    {
        return [
            'deadline_date' => 'datetime',
        ];
    }

    public function companyProfile()
    {
        return $this->belongsTo(CompanyProfile::class);
    }

    public function studyPrograms()
    {
        return $this->belongsToMany(StudyProgram::class);
    }

    public function applications()
    {
        return $this->hasMany(Application::class);
    }
}
