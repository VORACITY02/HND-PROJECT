<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('supervision_transfer_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('from_supervisor_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('to_supervisor_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('performed_by_admin_id')->constrained('users')->cascadeOnDelete();
            $table->timestamp('transferred_at');
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('supervision_transfer_logs');
    }
};
