<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('job_posting_study_program', function (Blueprint $table) {
            $table->id();
            $table->foreignId('job_posting_id')->constrained()->cascadeOnDelete();
            $table->foreignId('study_program_id')->constrained()->cascadeOnDelete();
            $table->timestamps();

            $table->unique(['job_posting_id', 'study_program_id'], 'job_posting_study_program_unique');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('job_posting_study_program');
    }
};
