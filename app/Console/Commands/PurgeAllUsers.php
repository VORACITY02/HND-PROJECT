<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class PurgeAllUsers extends Command
{
    protected $signature = 'app:purge-all-users {--force : Run without confirmation}';

    protected $description = 'Delete all users and related records (messages, profiles, supervisor applications), and reset auto-increments';

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

            Schema::disableForeignKeyConstraints();

            $tables = [
                'task_submissions',
                'internship_tasks',
                'supervisor_assignments',
                'supervision_requests',
                'message_user_reads',
                'messages',
                'personal_data',
                'profiles',
                'supervisor_applications',
                'admins',
                'staff',
                'students',
                'users',
            ];

            foreach ($tables as $table) {
                try {
                    DB::table($table)->truncate();
                } catch (\\Throwable $t) {
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
