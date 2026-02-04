<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('payment_accounts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            // Account identifier in the external payment simulator
            $table->string('external_account_id');
            $table->timestamps();

            $table->unique('user_id');
            $table->unique('external_account_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('payment_accounts');
    }
};
