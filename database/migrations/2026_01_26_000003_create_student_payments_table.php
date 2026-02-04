<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('student_payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')->constrained('users')->cascadeOnDelete();

            // Payment to the system account
            $table->unsignedBigInteger('amount_cents');
            $table->string('currency', 3)->default('XOF');

            $table->enum('status', ['pending', 'completed', 'failed'])->default('pending');

            $table->string('external_transfer_id')->nullable();
            $table->string('reference')->nullable();
            $table->text('note')->nullable();

            $table->timestamp('paid_at')->nullable();
            $table->timestamps();

            $table->index(['student_id', 'status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('student_payments');
    }
};
