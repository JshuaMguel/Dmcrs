<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RoomSeeder extends Seeder
{
    public function run(): void
    {
        // If you prefer to manage rooms via the UI, you can skip this seeder.
        // We'll make it safe/idempotent: it won't insert if rooms already exist.
        if (DB::table('rooms')->exists()) {
            return;
        }

        DB::table('rooms')->insert([
            [
                'name' => 'Room 101',
                'location' => 'Building A',
                'capacity' => 30,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Lab A',
                'location' => 'Building B',
                'capacity' => 20,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Auditorium',
                'location' => 'Main Building',
                'capacity' => 100,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
