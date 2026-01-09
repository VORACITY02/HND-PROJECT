<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        $driver = Schema::getConnection()->getDriverName();
        if ($driver === 'mysql') {
            // Allow negative grading by using a signed INT.
            DB::statement('ALTER TABLE task_submissions MODIFY grade_score INT NULL');
        }
    }

    public function down(): void
    {
        $driver = Schema::getConnection()->getDriverName();
        if ($driver === 'mysql') {
            // Revert to unsigned INT (best-effort)
            DB::statement('ALTER TABLE task_submissions MODIFY grade_score INT UNSIGNED NULL');
        }
    }
};
