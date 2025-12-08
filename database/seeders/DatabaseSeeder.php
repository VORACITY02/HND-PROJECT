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
        // No default users - admin will create all users manually
        // Uncomment below to create a default admin if needed:
        
        /*
        $admin = User::create([
            'name' => 'Admin User',
            'email' => 'admin@example.com',
            'password' => Hash::make('password'),
            'role' => 'admin',
            'email_verified_at' => now(),
        ]);
        Admin::create([
            'user_id' => $admin->id,
            'department' => 'Administration',
            'position' => 'System Administrator',
            'appointed_date' => now(),
        ]);
        */
    }
}
