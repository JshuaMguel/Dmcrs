<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\MakeUpClassRequest;
use App\Models\User;

class MakeUpClassRequestSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faculty = User::where('role', 'faculty')->first();

        // Create a pending request (will show in department chair dashboard)
        MakeUpClassRequest::updateOrCreate([
            'tracking_number' => 'TRK001',
        ], [
            'faculty_id' => $faculty->id,
            'subject' => 'Mathematics',
            'room' => 'Room 101',
            'reason' => 'Sick leave',
            'preferred_date' => now()->addDays(7),
            'preferred_time' => '10:00:00',
            'status' => 'pending',
            'attachment' => null,
        ]);

        // Create a request approved by chair (will show in academic head dashboard)
        MakeUpClassRequest::updateOrCreate([
            'tracking_number' => 'TRK002',
        ], [
            'faculty_id' => $faculty->id,
            'subject' => 'Physics',
            'room' => 'Room 202',
            'reason' => 'Conference attendance',
            'preferred_date' => now()->addDays(10),
            'preferred_time' => '14:00:00',
            'status' => 'CHAIR_APPROVED',
            'attachment' => null,
            'chair_remarks' => 'Approved for conference attendance',
        ]);

        // Create a request approved by academic head (will show in academic head dashboard)
        MakeUpClassRequest::updateOrCreate([
            'tracking_number' => 'TRK003',
        ], [
            'faculty_id' => $faculty->id,
            'subject' => 'Chemistry',
            'room' => 'Room 303',
            'reason' => 'Medical emergency',
            'preferred_date' => now()->addDays(5),
            'preferred_time' => '09:00:00',
            'status' => 'APPROVED',
            'attachment' => null,
            'chair_remarks' => 'Approved for medical reasons',
            'head_remarks' => 'Final approval granted',
        ]);
    }
}
