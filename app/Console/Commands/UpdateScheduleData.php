<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class UpdateScheduleData extends Command
{
    protected $signature = 'schedule:update-data';
    protected $description = 'Update schedule data with missing instructor names and subject titles';

    public function handle()
    {
        $this->info('Updating schedules with missing data...');

        // Update instructor names from users table
        $updated1 = DB::update('
            UPDATE schedules s
            JOIN users u ON s.instructor_id = u.id
            SET s.instructor_name = u.name
            WHERE s.instructor_name IS NULL OR s.instructor_name = ""
        ');

        $this->info("Updated {$updated1} schedule(s) with instructor names");

        // Update subject titles for makeup classes
        $updated2 = DB::update('
            UPDATE schedules
            SET subject_title = CONCAT("Make-up Class: ", IFNULL(subject_code, "Unknown Subject"))
            WHERE subject_title IS NULL OR subject_title = "" OR subject_title = "No Title"
        ');

        $this->info("Updated {$updated2} schedule(s) with subject titles");

        // Update sections that are TBA
        $updated3 = DB::update('
            UPDATE schedules
            SET section = "Make-up Class"
            WHERE section = "TBA" OR section IS NULL
        ');

        $this->info("Updated {$updated3} schedule(s) with sections");

        $this->info('Update complete!');
        return 0;
    }
}
