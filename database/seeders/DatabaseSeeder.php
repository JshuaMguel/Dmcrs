<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Room;
use App\Models\Student;
use Illuminate\Support\Facades\DB;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        // Seed departments and get their IDs
        $departments = [];
        foreach ([ 'BSIT', 'BTLED', 'BSA' ] as $deptName) {
            $dept = \App\Models\Department::updateOrCreate([
                'name' => $deptName
            ]);
            $departments[$deptName] = $dept->id;
        }

        $this->call(SubjectSeeder::class);

        // Seed a valid faculty user for MakeUpClassRequestSeeder
        $faculty = \App\Models\User::updateOrCreate([
            'email' => 'faculty@production.com',
        ], [
            'name' => 'Production Faculty',
            'role' => 'faculty',
            'password' => bcrypt('password'),
            'department_id' => $departments['BSIT'] ?? 1,
        ]);

        $this->call(MakeUpClassRequestSeeder::class);
        $this->call(SuperAdminSeeder::class);
    }
}
