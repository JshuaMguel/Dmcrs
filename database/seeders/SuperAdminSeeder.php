<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class SuperAdminSeeder extends Seeder
{
    public function run(): void
    {
        User::updateOrCreate([
            'email' => 'admin@ustp.edu.ph',
        ], [
            'name' => 'System Admin',
            'password' => Hash::make('admin2025'),
            'role' => 'super_admin',
            'department_id' => null,
        ]);
    }
}
