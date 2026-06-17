<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\Attributes\Fillable;

#[Fillable(['job_posting_id', 'student_profile_id', 'status'])]
class Application extends Model
{
    protected function casts(): array
    {
        return [
            'status' => \App\Enums\ApplicationStatusEnum::class,
        ];
    }

    public function jobPosting()
    {
        return $this->belongsTo(JobPosting::class);
    }

    public function studentProfile()
    {
        return $this->belongsTo(StudentProfile::class);
    }
}
