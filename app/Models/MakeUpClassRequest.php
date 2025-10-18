<?php

namespace App\Models;

use Illuminate\Support\Facades\Log;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MakeUpClassRequest extends Model
{
    use HasFactory;

    protected $fillable = [
        'faculty_id',
        'subject_id',
        'section_id',
        'subject',
        'subject_title',
        'room',
        'reason',
        'preferred_date',
        'preferred_time',
        'end_time',
        'status',
        'attachment',
        'student_list',
        'tracking_number',
        'chair_remarks',
        'head_remarks',
    ];

    /**
     * Notify academic head about status change
     */
    public function notifyAcademicHead(string $status, ?string $remarks = null)
    {
        $head = \App\Models\User::where('role', 'academic_head')->first();
        if ($head) {
            $head->notify(new \App\Notifications\MakeupClassStatusNotification($this, $status, $remarks));
        }
    }

    // Relationship with Faculty (User)
    public function faculty()
    {
        return $this->belongsTo(User::class, 'faculty_id');
    }

    // Relationship with Subject
    public function subject()
    {
        return $this->belongsTo(\App\Models\Subject::class, 'subject_id');
    }

    // Relationship with Section
    public function section()
    {
        return $this->belongsTo(\App\Models\Section::class, 'section_id');
    }

    /**
     * Notify the faculty about status change
     */
    public function notifyStatusChange(string $status, ?string $remarks = null)
    {
        $this->faculty->notify(new \App\Notifications\MakeupClassStatusNotification($this, $status, $remarks));
    }

    /**
     * Notify students about approved make-up class
     */
    public function notifyStudents(array $studentEmails)
    {
        $successCount = 0;
        $failedCount = 0;

        foreach ($studentEmails as $email) {
            try {
                Log::info('Sending makeup class notification to student email: ' . $email);
                \Illuminate\Support\Facades\Mail::to($email)->send(new \App\Mail\MakeupClassStudentNotification($this, $email));
                $successCount++;
                Log::info('Successfully sent email to: ' . $email);
            } catch (\Exception $e) {
                $failedCount++;
                Log::error('Failed to send email to: ' . $email . ' - Error: ' . $e->getMessage());
            }
        }

        Log::info('Email summary: ' . $successCount . ' successful, ' . $failedCount . ' failed out of ' . count($studentEmails) . ' total students');
    }

    /**
     * Notify department chair about new request
     */
    public function notifyDepartmentChair()
    {
        $this->loadMissing('faculty');

        $departmentId = optional($this->faculty)->department_id;

        $chairsQuery = \App\Models\User::where('role', 'department_chair');

        if ($departmentId) {
            $chairsQuery->where('department_id', $departmentId);
        }

        $departmentChairs = $chairsQuery->get();

        foreach ($departmentChairs as $departmentChair) {
            $departmentChair->notify(new \App\Notifications\MakeupClassStatusNotification($this, 'new_request'));
        }

        // Also notify academic head about new request in the system
        $academicHead = \App\Models\User::where('role', 'academic_head')->first();
        if ($academicHead) {
            $academicHead->notify(new \App\Notifications\MakeupClassStatusNotification($this, 'new_request_submitted'));
        }
    }
}
