<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Gate;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Policy registration (this app does not have an AuthServiceProvider)
        Gate::policy(\App\Models\InternshipTask::class, \App\Policies\InternshipTaskPolicy::class);
        Gate::policy(\App\Models\SupervisorAssignment::class, \App\Policies\SupervisorAssignmentPolicy::class);
        // TaskSubmission policy is ability-based (grade)
        Gate::define('grade-submission', [\App\Policies\TaskSubmissionPolicy::class, 'grade']);

        // Share flags with all views; be defensive if DB is not reachable
        try {
            $hasPersonal = \Illuminate\Support\Facades\Schema::hasTable('personal_data');
        } catch (\Throwable $e) {
            $hasPersonal = false;
        }
        try {
            $hasApps = \Illuminate\Support\Facades\Schema::hasTable('supervisor_applications');
        } catch (\Throwable $e) {
            $hasApps = false;
        }
        \Illuminate\Support\Facades\View::share('hasPersonalDataTable', $hasPersonal);
        \Illuminate\Support\Facades\View::share('hasSupervisorApplicationsTable', $hasApps);
    }
}
