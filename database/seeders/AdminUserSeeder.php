<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Department;

class AdminUserSeeder extends Seeder
{
    /**
     * Run the database seeder.
     */
    public function run(): void
    {
        // Create Department first if it doesn't exist
        $department = Department::firstOrCreate(
            ['department_name' => 'Information Technology'],
            [
                'department_name' => 'Information Technology',
                'department_code' => 'IT'
            ]
        );

        // Create Admin User
        User::firstOrCreate(
            ['email' => 'admin@ustp.edu.ph'],
            [
                'first_name' => 'System',
                'last_name' => 'Administrator',
                'email' => 'admin@ustp.edu.ph',
                'password' => Hash::make('admin2025'),
                'role' => 'admin',
                'employee_id' => 'ADMIN001',
                'department_id' => $department->id,
                'email_verified_at' => now(),
            ]
        );

        $this->command->info('Admin user created successfully!');
        $this->command->info('Email: admin@ustp.edu.ph');
        $this->command->info('Password: admin2025');
    }
}