<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class PurgeAllUsers extends Command
{
    /**
     * The name and signature of the console command.
     * --force will skip confirmation
     */
    protected $signature = 'app:purge-all-users {--force : Run without confirmation}';

    /**
     * The console command description.
     */
    protected $description = 'Delete all users and related records (messages, profiles, supervisor applications), and reset auto-increments';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        if (!$this->option('force')) {
            if (!$this->confirm('This will permanently delete ALL users and related data. Continue?')) {
                $this->warn('Aborted.');
                return self::SUCCESS;
            }
        }

        try {
            $driver = DB::getDriverName();

            // Preferred: Laravel Schema helpers to handle FKs across drivers
            Schema::disableForeignKeyConstraints();

            // Delete dependent tables first
            $tables = [
                // New entities first (child-most)
                'task_submissions',
                'internship_tasks',
                'supervisor_assignments',
                'supervision_requests',
                // Existing messaging and profile data
                'message_user_reads',
                'messages',
                'personal_data',
                'profiles',
                'supervisor_applications',
                // Role tables and users last
                'admins',
                'staff',
                'students',
                'users',
            ];

            foreach ($tables as $table) {
                try {
                    // Truncate where supported
                    DB::table($table)->truncate();
                } catch (\Throwable $t) {
                    // Fallback for SQLite and others: delete all rows
                    DB::table($table)->delete();
                }
            }

            Schema::enableForeignKeyConstraints();

            $this->info('All users and related records have been purged successfully.');
            return self::SUCCESS;
        } catch (\Throwable $e) {
            $this->error('Failed to purge: ' . $e->getMessage());
            return self::FAILURE;
        }
    }
}
