<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Admin;
use App\Models\Staff;
use App\Models\Student;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Create 2 Admins, 2 Staff, 2 Students (users)
        $users = [];

        // Admins
        $users['admin1'] = User::create([
            'name' => 'Admin One',
            'email' => 'admin1@example.com',
            'password' => Hash::make('password'),
            'role' => 'admin',
            'email_verified_at' => now(),
        ]);
        $users['admin2'] = User::create([
            'name' => 'Admin Two',
            'email' => 'admin2@example.com',
            'password' => Hash::make('password'),
            'role' => 'admin',
            'email_verified_at' => now(),
        ]);
        Admin::create(['user_id' => $users['admin1']->id, 'appointed_date' => now()]);
        Admin::create(['user_id' => $users['admin2']->id, 'appointed_date' => now()]);

        // Staff
        $users['staff1'] = User::create([
            'name' => 'Staff One',
            'email' => 'staff1@example.com',
            'password' => Hash::make('password'),
            'role' => 'staff',
            'email_verified_at' => now(),
        ]);
        $users['staff2'] = User::create([
            'name' => 'Staff Two',
            'email' => 'staff2@example.com',
            'password' => Hash::make('password'),
            'role' => 'staff',
            'email_verified_at' => now(),
        ]);
        Staff::create(['user_id' => $users['staff1']->id, 'joined_date' => now()]);
        Staff::create(['user_id' => $users['staff2']->id, 'joined_date' => now()]);

        // Students (users)
        $users['student1'] = User::create([
            'name' => 'Student One',
            'email' => 'student1@example.com',
            'password' => Hash::make('password'),
            'role' => 'user',
            'email_verified_at' => now(),
        ]);
        $users['student2'] = User::create([
            'name' => 'Student Two',
            'email' => 'student2@example.com',
            'password' => Hash::make('password'),
            'role' => 'user',
            'email_verified_at' => now(),
        ]);
        Student::create(['user_id' => $users['student1']->id, 'enrollment_date' => now()]);
        Student::create(['user_id' => $users['student2']->id, 'enrollment_date' => now()]);
    }
}
