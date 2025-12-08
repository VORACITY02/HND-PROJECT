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
        Schema::create('staff', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->date('joined_date')->nullable();
            $table->timestamps();
            
            $table->index('user_id');
            
            // Future fields (can be added later):
            // $table->string('department')->nullable();
            // $table->string('position')->nullable();
            // $table->string('employee_id')->unique()->nullable();
            // $table->string('phone')->nullable();
            // $table->text('specialization')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('staff');
    }
};
