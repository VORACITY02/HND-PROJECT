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
        Schema::create('students', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->date('enrollment_date')->nullable();
            $table->timestamps();
            
            $table->index('user_id');
            
            // Future fields (can be added later):
            // $table->string('student_id')->unique()->nullable();
            // $table->string('phone')->nullable();
            // $table->string('university')->nullable();
            // $table->string('major')->nullable();
            // $table->string('year_of_study')->nullable();
            // $table->decimal('gpa', 3, 2)->nullable();
            // $table->text('skills')->nullable();
            // $table->text('bio')->nullable();
            // $table->string('resume_path')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('students');
    }
};
