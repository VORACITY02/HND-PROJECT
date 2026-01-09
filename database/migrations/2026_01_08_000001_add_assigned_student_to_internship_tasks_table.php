<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        // Column is created in the base migration; here we only add the FK constraint for supported drivers.
        if (Schema::getConnection()->getDriverName() !== 'sqlite') {
            Schema::table('internship_tasks', function (Blueprint $table) {
                $table->foreign('assigned_student_id')
                    ->references('id')
                    ->on('users')
                    ->nullOnDelete();
            });
        }
    }

    public function down(): void
    {
        if (Schema::getConnection()->getDriverName() !== 'sqlite') {
            Schema::table('internship_tasks', function (Blueprint $table) {
                $table->dropForeign(['assigned_student_id']);
            });
        }
    }
};
