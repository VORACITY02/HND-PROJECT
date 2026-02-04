<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('staff_payouts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('staff_id')->constrained('users')->cascadeOnDelete();

            $table->unsignedBigInteger('amount_cents');
            $table->string('currency', 3)->default('XOF');

            // snapshot of how we computed it
            $table->unsignedInteger('supervisee_count')->default(0);
            $table->unsignedBigInteger('base_pay_cents')->default(0);
            $table->unsignedBigInteger('supervisor_fixed_bonus_cents')->default(0);
            $table->unsignedBigInteger('per_supervisee_bonus_cents')->default(0);

            $table->enum('status', ['pending', 'paid', 'failed'])->default('pending');
            $table->string('external_transfer_id')->nullable();
            $table->string('reference')->nullable();
            $table->text('note')->nullable();
            $table->timestamp('paid_at')->nullable();
            $table->timestamps();

            $table->index(['staff_id', 'status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('staff_payouts');
    }
};
