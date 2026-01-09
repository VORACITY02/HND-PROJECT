<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('task_submissions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('task_id')->constrained('internship_tasks')->cascadeOnDelete();
            $table->foreignId('student_id')->constrained('users')->cascadeOnDelete();
            $table->timestamp('submitted_at')->nullable();
            $table->string('file_path')->nullable();
            $table->text('content')->nullable();
            // Signed int: allow negative grading
            $table->integer('grade_score')->nullable();
            $table->timestamp('graded_at')->nullable();
            $table->enum('status', ['submitted','graded','rejected'])->default('submitted');
            $table->timestamps();
            $table->unique(['task_id','student_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('task_submissions');
    }
};