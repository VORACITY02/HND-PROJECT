<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('internship_tasks', function (Blueprint $table) {
            if (!Schema::hasColumn('internship_tasks', 'deleted_at')) {
                $table->softDeletes();
            }
            if (!Schema::hasColumn('internship_tasks', 'is_special')) {
                $table->boolean('is_special')->default(false)->after('active');
            }
        });
    }

    public function down(): void
    {
        Schema::table('internship_tasks', function (Blueprint $table) {
            if (Schema::hasColumn('internship_tasks', 'deleted_at')) {
                $table->dropSoftDeletes();
            }
            if (Schema::hasColumn('internship_tasks', 'is_special')) {
                $table->dropColumn('is_special');
            }
        });
    }
};
