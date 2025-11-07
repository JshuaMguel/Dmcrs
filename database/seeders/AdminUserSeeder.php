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
        // Find existing Information Technology department (if exists from DepartmentSeeder)
        // If not found, admin user will have null department_id (can be assigned later)
        $department = Department::where('name', 'like', '%Information Technology%')->first();

        // Create Admin User
        User::firstOrCreate(
            ['email' => 'admin@ustp.edu.ph'],
            [
                'name' => 'System Administrator',
                'email' => 'admin@ustp.edu.ph',
                'password' => Hash::make('admin2025'),
                'role' => 'admin',
                'department_id' => $department?->id, // Use null if no department found
                'email_verified_at' => now(),
            ]
        );

        $this->command->info('Admin user created successfully!');
        $this->command->info('Email: admin@ustp.edu.ph');
        $this->command->info('Password: admin2025');
    }
}