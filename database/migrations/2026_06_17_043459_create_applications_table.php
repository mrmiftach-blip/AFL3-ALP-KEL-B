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
        Schema::create('applications', function (Blueprint $table) {
            $table->id();
            $table->foreignId('job_posting_id')->constrained()->onDelete('cascade');
            $table->foreignId('student_profile_id')->constrained()->onDelete('cascade');
            $table->enum('status', ['Submitted', 'Reviewed', 'Accepted', 'Rejected'])->default('Submitted');
            $table->timestamps();

            $table->unique(['job_posting_id', 'student_profile_id']); // Pencegahan Double-Apply
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('applications');
    }
};
